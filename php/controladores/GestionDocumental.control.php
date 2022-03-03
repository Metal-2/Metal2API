<?php


class GestionDocumentalControlador extends Controladores{
  
    function todosDocumentosFiltro() {
       $busqueda=isset($this->busqueda)?$this->busqueda:NULL;
       $inicioBusqueda=isset($this->inicioBusqueda)?$this->inicioBusqueda:NULL;
       $cantidad=isset($this->cantidad)?$this->cantidad:NULL;
  
      if(is_null($inicioBusqueda) && is_null($busqueda)){
        $datos=GestionDocumental::porRangodDocumentos(0,$cantidad);
        $cantidadTotal=GestionDocumental::cantidadTotalDocumentos();
          
        echo Respuestas::exito(['Datos'=>$datos,'CantidadTotal'=>$cantidadTotal]);
       
      }else{
         if(empty($busqueda) || is_null($busqueda)){
         	$datos=GestionDocumental::porRangodDocumentos($inicioBusqueda,$cantidad);
         }else{
			$datos=GestionDocumental::porFiltroYRangoDocumentos($busqueda,$inicioBusqueda,$cantidad);
         }
         echo Respuestas::exito($datos);
      }
        
    }

    public function mostrarDocumentosPorCarpeta()
    {
        $carpetaDocumentoID = empty($this->carpetaDocumentoID)?NULL:$this->carpetaDocumentoID;
        if(isset($carpetaDocumentoID)){
            $carpetaActual=GestionDocumental::CarpetaDato($carpetaDocumentoID);
            $carpetasHijas=GestionDocumental::carpetasHijas($carpetaDocumentoID);
            $listaDocumentos=GestionDocumental::documentosPorCarpetaID($carpetaDocumentoID);
            echo Respuestas::exito(["carpetaActual"=>$carpetaActual,"carpetas"=>$carpetasHijas,"documentos"=>$listaDocumentos]);
        }else{
            echo Respuestas::error("Datos insuficientes para la operación.");
        }
    }

    public function mostrarDocumentosPorCarpetaCodigo()
    {
        $carpetaDocumentoCODIGO = empty($this->carpetaDocumentoCODIGO)?NULL:$this->carpetaDocumentoCODIGO;
        if(isset($carpetaDocumentoCODIGO)){
            $carpetaActual=GestionDocumental::CarpetaDatoPorCodigo($carpetaDocumentoCODIGO);
            if($carpetaActual){
                $carpetaDocumentoID=$carpetaActual->carpetaDocumentoID;
                $carpetasHijas=GestionDocumental::carpetasHijas($carpetaDocumentoID);
                $listaDocumentos=GestionDocumental::documentosPorCarpetaID($carpetaDocumentoID);
                echo Respuestas::exito(["carpetaActual"=>$carpetaActual,"carpetas"=>$carpetasHijas,"documentos"=>$listaDocumentos]);
            }else{
                echo Respuestas::error("El codigo de la carpeta no existe en la base de datos.");
            }
        }else{
            echo Respuestas::error("Datos insuficientes para la operación.");
        }
    }
    
    public function guardarCarpeta(){
        $carpetaPadre=GestionDocumental::CarpetaDato($this->carpetaDocumentoPADRE);
        $nombreLimpio = Caracteres::limpiarCadena($this->carpetaDocumentoTITULO);
        $rutaCarpeta = $carpetaPadre->carpetaDocumentoURL."/".$nombreLimpio;
        if (!file_exists($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0777, true);
        }
        $guardar=GestionDocumental::gestionCarpetaMasiva(
            $this->carpetaDocumentoTITULO,
            $rutaCarpeta,
            $carpetaPadre->carpetaDocumentoID,
            $nombreLimpio
        );

        if ($guardar) {
            echo Respuestas::exito("Carpeta creada con exito.");
        }else{
            echo Respuestas::error("Error al guardar la carpeta.");
        }
    }

    public function guardarDocumento(){
      $file=empty($this->documentoArchivo)?NULL:$this->documentoArchivo;
      $documentoTITULO=empty($this->documentoTITULO)?NULL:$this->documentoTITULO;
      $carpetaDocumentoID=empty($this->carpetaDocumentoID)?NULL:$this->carpetaDocumentoID;
      $documentoESTADO=empty($this->documentoESTADO)?NULL:$this->documentoESTADO;
      $documentoUSR=empty($this->usrLogin)?NULL:$this->usrLogin;
      $documentoID=empty($this->documentoID)?NULL:$this->documentoID;
      
      if(isset($documentoTITULO,$carpetaDocumentoID,$documentoUSR)){
        $carpeta=GestionDocumental::CarpetaDato($carpetaDocumentoID);
        if(isset($file)){
            $extensionFile=pathinfo($file['name'], PATHINFO_EXTENSION);
            $documentoURL=$carpeta->carpetaDocumentoURL."/".uniqid().".".$extensionFile;
        }
        if(!isset($documentoID)){
            if(isset($file)){
                if($this->subirArchivo($file, $documentoURL)){
                    $documentoGuardado=GestionDocumental::guardarDocumento($documentoTITULO,$documentoURL,$carpetaDocumentoID,$documentoUSR,$extensionFile,$documentoESTADO);
                    if($documentoGuardado){
                        echo Respuestas::exito("Documento Guardado con exito.");
                    }else{
                        echo Respuestas::error("Error al Guardar el documento.");
                     }
                }else{
                    echo Respuestas::error("Error al subir el archivo.");
                }
            }else{
                echo Respuestas::error("Por favor seleccione un archivo.");
            }
        }else{
            $documento=GestionDocumental::documento($documentoID);
            if($documento){
                if($documento->carpetaDocumentoID!=$carpetaDocumentoID){
                    if(isset($file)){
                        $this->eliminarArchivo($documento->documentoURL);
                        $this->subirArchivo($file,$documentoURL);
                    }else{
                        $arrayRuta=explode("/", $documento->documentoURL);
                        $documentoURL=$carpeta->carpetaDocumentoURL."/".end($arrayRuta);
                        $this->moverArchivo($documento->documentoURL, $documentoURL);
                    }
                }else{
                    if(isset($file)){
                        $this->eliminarArchivo($documento->documentoURL);
                        $this->subirArchivo($file,$documentoURL);
                    }
                }
                $extensionFile=isset($extensionFile)?$extensionFile:$documento->documentoTIPO;
                $documentoURL=isset($documentoURL)?$documentoURL:$documento->documentoURL;
                GestionDocumental::actualizarDocumento($documentoID,$documentoTITULO,$documentoURL,$carpetaDocumentoID,$documentoUSR,$extensionFile,$documentoESTADO); 
                echo Respuestas::exito("Documento Actualizado");
            }else{
                echo Respuestas::error("Errror al encontrar el documento.");
            }
        }
      }
      
    }


    public function eliminarDocumento(){
        $documentoID=empty($this->documentoID)?NULL:$this->documentoID;
        if(isset($documentoID)){
            $documento=GestionDocumental::documento($documentoID);
            if(GestionDocumental::eliminar( $documento->documentoID)){
                $this->eliminarArchivo($documento->documentoURL);
                echo Respuestas::exito("Documento Eliminado");
            }else{
                echo Respuestas::error("Error al eliminar el documento.");
            }
        }else{
            echo Respuestas::error("Datos insuficientes para la operación.");
        }
    }

    public function subirArchivo($file,$documentoURL){
        return move_uploaded_file($file['tmp_name'], $documentoURL);
    }

    public function moverArchivo($rutaActual,$rutaNueva){
        if (copy($rutaActual,$rutaNueva)) {
           return $this->eliminarArchivo($rutaActual);
        }else{
            return false;
        }
    }

    public function eliminarArchivo($ruta){
       return unlink($ruta);
    }
  	public function documentoPorId(){
      $documentoID=empty($this->documentoID)?NULL:$this->documentoID;
      $accion=$this->definirAccionDocumento();
      $usuarioID=empty($this->usrLogin)?NULL:$this->usrLogin;

      if(isset($documentoID)){
         $documento=GestionDocumental::documento($documentoID);
        if($documento){
            if ($usuarioID) {
                ControlAccionesDocumentos::registrarAccion($usuarioID,$documentoID,$accion);
            }
            echo Respuestas::exito($documento);
        }else{
          echo Respuestas::error("No se encontro el documento.");
        }
      }else{
        echo Respuestas::error("Datos incompletos para la operación.");
      }
    }

    public function definirAccionDocumento($accionUsuario=NULL){
        if ($accionUsuario == ControlAccionesDocumentos::DESCARGA) {
                return ControlAccionesDocumentos::DESCARGA ;
        }
        return ControlAccionesDocumentos::VER ;
    }
    
    public function registrarAccionesDocumentosUsuario(){
        $usuarioID=empty($this->usuarioID)?NULL:$this->usuarioID;
        $arrayDocumentosID=empty($this->arrayDocumentosID)?NULL:$this->arrayDocumentosID;
        $accionUsuario=empty($this->accionUsuario)?NULL:$this->accionUsuario;
        if(isset($usuarioID,$arrayDocumentosID,$accionUsuario)){
            $accion="";
            switch ($accionUsuario) {
                case ControlAccionesDocumentos::VER:
                    $accion=ControlAccionesDocumentos::VER ;
                    break;
                case ControlAccionesDocumentos::DESCARGA :
                    $accion=ControlAccionesDocumentos::DESCARGA ;
                    break;
            }
            if($accion != ""){
                $count=0;
                $documentos=[];
                foreach ($arrayDocumentosID as $key => $documentoID) {
                    $documento=GestionDocumental::documento($documentoID);
                    if($documento){
                        array_push($documentos,$documento);
                        $controlAccionDocumentoID=ControlAccionesDocumentos::registrarAccion($usuarioID,$documento->documentoID,$accion);
                        if($controlAccionDocumentoID){
                            $count += 1;
                        }
                    }
                }
                if($count==count($arrayDocumentosID)){
                    echo Respuestas::exito("Acción registrada",$documentos);
                }else{
                    echo Respuestas::alerta("La acción no se pudo registar");
                }
            }else{
                echo Respuestas::error("Esta acción no se encuentra registrada.");
            }
        }
    }

    public function obtener_estructura_directorios_y_guardar($ruta="INTRANET_ARCHIVOS", $carpetaDocumentoID=1){

        if ($carpetaDocumentoID == 1) {
            GestionDocumental::grearCarpetaBase();
        }
        // Se comprueba que realmente sea la ruta de un directorio
        if (is_dir($ruta)){
           // Abre un gestor de directorios para la ruta indicada
           $textError="";
           $countExito=0;
           $separador = "/";
           $rutaSeparada = explode($separador, $ruta);
           $gestor = opendir($ruta);
           // Recorre todos los elementos del directorio
           while (($archivo = readdir($gestor)) !== false)  {
              
               $ruta_completa = $ruta . "/" . $archivo;
               
               // Se muestran todos los archivos y carpetas excepto "." y ".."
               if ($archivo != "." && $archivo != "..") {
                   $archivoLimpio=Caracteres::limpiarCadena($archivo);
                   $ruta_completa_limpia = $ruta . "/" . $archivoLimpio;
                   if(rename($ruta_completa,$ruta_completa_limpia)){
                     $archivo=$archivoLimpio;
                     $ruta_completa=$ruta_completa_limpia;
                   }
                   // Si es un directorio se recorre recursivamente
                   if (is_dir($ruta_completa)) {
                       $carpetaDocumentoIDGuardado=GestionDocumental::gestionCarpetaMasiva($archivo,$ruta_completa,$carpetaDocumentoID,$archivo);
                       if($carpetaDocumentoIDGuardado){
                           $this->obtener_estructura_directorios_y_guardar($ruta_completa,$carpetaDocumentoIDGuardado);
                           $countExito = $countExito + 1;
                       }else{
                           $textError .= "Carpeta : " . $archivo;
                       }
                   } else {
                       $documentoTIPO = pathinfo($archivo, PATHINFO_EXTENSION);
                       if(GestionDocumental::gestionDocumentoMasiva($archivo,$ruta_completa,$carpetaDocumentoID,1,$documentoTIPO)){
                           $countExito = $countExito + 1;
                       }else{
                           $textError .= "Archivo : ". $archivo;
                       }
                   }

               }
           }
           // Cierra el gestor de directorios
           closedir($gestor);
           if($textError==""){
                echo $countExito ." Archivos Guardados exitosamente en la base de datos"; 
           }else{
               echo "Lo siguientes archivos tienen inconvenientes al insertarse en la base de datos [".$textError."]**************";
           }
       } else {
           echo "No es una ruta de directorio valida.";
       }
   }
}