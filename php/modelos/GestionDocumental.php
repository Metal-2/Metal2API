<?php 
 class GestionDocumental{
    
   const ACTIVO="ACTIVO";
   const DESACTIVO="DESACTIVO";
   const CARPETA_BASE_ID = 1;
    const SELECTCARPETAS=<<<EOD
        SELECT * FROM `intranet`.`carpetasdocumentos`     
EOD;
    const SELECTDOCUMENTOS=<<<DOC
        SELECT * FROM `intranet`.`documentos`
DOC;
   const DATOS_COMPLETOS_DOCUMENTOS=<<<DOC
        SELECT 
            carpetasdocumentos.* ,
     		documentos.* 
     
        FROM `intranet`.`documentos`
        LEFT JOIN `intranet`.`carpetasdocumentos` 
           ON documentos.carpetaDocumentoID = carpetasdocumentos.carpetaDocumentoID
DOC;
	
   	public static function cantidadTotalDocumentos(){
       $sql=<<<EOD
            SELECT
            	COUNT(*) as cantidadTotal
            FROM
                `intranet`.`documentos` 
EOD;
        return Conexion::selectUnaFila($sql);
    }
   
    public static function porRangodDocumentos($inicioBusqueda,$cantidad){
      $sql=self::DATOS_COMPLETOS_DOCUMENTOS;
      $sql .= " ORDER BY documentos.documentoID DESC LIMIT $inicioBusqueda,$cantidad ";
     
      return Conexion::selectVariasFilas($sql);
    }
   
   public static function porFiltroYRangoDocumentos($busqueda,$inicioBusqueda,$cantidad){
      $sql=self::DATOS_COMPLETOS_DOCUMENTOS;
      $sql .= " WHERE documentos.documentoTITULO LIKE '%$busqueda%' OR documentos.documentoTIPO LIKE '%$busqueda%' OR documentos.documentoFCHCREO LIKE '%$busqueda%' OR carpetasdocumentos.carpetaDocumentoTITULO LIKE '%$busqueda%' ORDER BY documentos.documentoID DESC LIMIT $inicioBusqueda,$cantidad ";
      return Conexion::selectVariasFilas($sql);
   }
   
   	public static function cantidadTotalCarpetas(){
       $sql=<<<EOD
            SELECT
            	COUNT(*) as cantidadTotal
            FROM
                `intranet`.`carpetasdocumentos` 
EOD;
        return Conexion::selectUnaFila($sql);
    }
    
   
    public static function CarpetaTodos($carpetaDocumentoESTADO=NULL){
        $datos=[]; 
        $sql=self::SELECTCARPETAS;
       
        if(isset($carpetaDocumentoESTADO)){
          $sq .= "WHERE carpetaDocumentoESTADO = ? ";
          array_push($datos,$carpetaDocumentoESTADO);
        }

        $sql .= " ORDER BY carpetaDocumentoTITULO"; 
        return Conexion::selectVariasFilas($sql,$datos);
    }
   
    public static function CarpetaDato($carpetaDocumentoID){
        $sql=self::SELECTCARPETAS."WHERE carpetaDocumentoID = ?";
        return Conexion::selectUnaFila($sql,[$carpetaDocumentoID]);
    }

    public static function CarpetaDatoPorCodigo($carpetaDocumentoCODIGO){
        $sql=self::SELECTCARPETAS."WHERE carpetaDocumentoCODIGO = ?";
        return Conexion::selectUnaFila($sql,[$carpetaDocumentoCODIGO]);
    }

    public static function carpetaValida($carpetaDocumentoURL,$carpetaDocumentoPADRE){
        $sql=self::SELECTCARPETAS."WHERE carpetaDocumentoURL = ? AND carpetaDocumentoPADRE = ?";
        return Conexion::selectUnaFila($sql,[$carpetaDocumentoURL,$carpetaDocumentoPADRE]);
    }

    public  static function carpetasHijas($carpetaDocumentoID){
        $sql = self::SELECTCARPETAS . "WHERE carpetaDocumentoPADRE = ? ";
        $sql .="ORDER BY `carpetasdocumentos`.`carpetaDocumentoID` DESC";
        return Conexion::selectVariasFilas($sql,[$carpetaDocumentoID]);
    }

    public static function documentosPorCarpetaID($carpetaDocumentoID){
        $sql= self::SELECTDOCUMENTOS . "WHERE carpetaDocumentoID = ? AND documentoESTADO = ? ";
        $sql .=" ORDER BY `documentos`.`documentoID` DESC";
        return Conexion::selectVariasFilas($sql,[$carpetaDocumentoID, self::ACTIVO]);
    }

    public static function documento($documentoID){
        $sql=self::SELECTDOCUMENTOS."WHERE `documentoID` = ?";
        return Conexion::selectUnaFila($sql,[$documentoID]);
    }

    public static function documentoValida($documentoURL,$carpetaDocumentoID){
        $sql=self::SELECTDOCUMENTOS."WHERE `documentoURL` = ? AND `carpetaDocumentoID` = ?";
        return Conexion::selectUnaFila($sql,[$documentoURL,$carpetaDocumentoID]);
    }

    public static function guardarDocumento($documentoTITULO,$documentoURL,$carpetaDocumentoID,$documentoUSRCREO,$documentoTIPO=NULL,$documentoESTADO="ACTIVO"){
        $sql=<<<EOD
            INSERT INTO  `intranet`.`documentos` (documentoTITULO, documentoURL, carpetaDocumentoID,documentoUSRCREO,documentoTIPO,documentoHASH,documentoESTADO)
            VALUES (?,?,?,?,?,?,?)
EOD;
       return Conexion::insertFila($sql,[$documentoTITULO,$documentoURL,$carpetaDocumentoID,$documentoUSRCREO,$documentoTIPO, uniqid(),$documentoESTADO]);
   }

   public static function actualizarDocumento($documentoID,$documentoTITULO,$documentoURL,$carpetaDocumentoID,$documentoUSRCREO,$documentoTIPO=NULL,$documentoESTADO="ACTIVO"){
    $sql=<<<EOD
        UPDATE  `intranet`.`documentos` SET 
        documentoTITULO =?, 
        documentoURL=?, 
        carpetaDocumentoID=?,
        documentoUSRCREO=?,
        documentoTIPO=?,
        documentoESTADO=?
        WHERE documentoID=?
EOD;
   return Conexion::actualizarFila($sql,[$documentoTITULO,$documentoURL,$carpetaDocumentoID,$documentoUSRCREO,$documentoTIPO,$documentoESTADO,$documentoID]);
}


   public static function eliminar($documentoID){
       $sql="DELETE FROM `intranet`.`documentos` WHERE documentoID=?";
       return Conexion::eliminarFila($sql,[$documentoID]);
   }

   public static function guardarCarpeta($carpetaDocumentoTITULO,$carpetaDocumentoURL,$carpetaDocumentoPADRE=NULL,$carpetaDocumentoCODIGO=NULL){
      $sql=<<<EOD
        INSERT INTO  `intranet`.`carpetasdocumentos` (carpetaDocumentoTITULO, carpetaDocumentoURL, carpetaDocumentoPADRE,carpetaDocumentoCODIGO)
        VALUES (?,?,?,?)
EOD;
       return Conexion::insertFila($sql,[$carpetaDocumentoTITULO,$carpetaDocumentoURL,$carpetaDocumentoPADRE,$carpetaDocumentoCODIGO]);
    }

    public static function grearCarpetaBase() 
    {
       $carpetaBase = self::CarpetaDato(self::CARPETA_BASE_ID);
       if(!$carpetaBase){
            self::guardarCarpeta("INTRANET_ARCHIVOS", "INTRANET_ARCHIVOS", NULL, NULL);
       }
    }
   
   	public static function gestionCarpeta($carpetaDocumentoTITULO,$carpetaDocumentoURL,$carpetaDocumentoPADRE,$carpetaDocumentoESTADO=self::ACTIVO){
         
        $carpeta=self::carpetaValida($carpetaDocumentoURL,$carpetaDocumentoPADRE);

        if(!$carpeta){
            if(isset($carpetaDocumentoCODIGO)){
                $carpetaDocumentoCODIGO=Caracteres::limpiarCadena($carpetaDocumentoTITULO);
                $carpetaDocumentoCODIGO = strtoupper(uniqid()."-".$carpetaDocumentoCODIGO);
            }
            return  self::guardarCarpeta($carpetaDocumentoTITULO,$carpetaDocumentoURL,$carpetaDocumentoPADRE,$carpetaDocumentoCODIGO);
        }
         return $carpeta->carpetaDocumentoID;
    }

    public static function gestionCarpetaMasiva($carpetaDocumentoTITULO,$carpetaDocumentoURL,$carpetaDocumentoPADRE,$carpetaDocumentoCODIGO){
         
        $carpeta=self::carpetaValida($carpetaDocumentoURL,$carpetaDocumentoPADRE);

        if(!$carpeta){
            if(isset($carpetaDocumentoCODIGO)){
                $carpetaDocumentoCODIGO=Caracteres::limpiarCadena($carpetaDocumentoCODIGO);
                $carpetaDocumentoCODIGO = strtoupper(uniqid()."-".$carpetaDocumentoCODIGO);
            }
            return  self::guardarCarpeta($carpetaDocumentoTITULO,$carpetaDocumentoURL,$carpetaDocumentoPADRE,$carpetaDocumentoCODIGO);
        }
         return $carpeta->carpetaDocumentoID;
    }

    public static function gestionDocumentoMasiva($documentoTITULO,$documentoURL,$carpetaDocumentoID,$documentoUSRCREO,$documentoTIPO=NULL){
        $documento=self::documentoValida($documentoURL,$carpetaDocumentoID);
        if(!$documento){
            echo "***********************";
             echo "$documentoTITULO";
             echo "-> $carpetaDocumentoID ******************";
            return  self::guardarDocumento($documentoTITULO,$documentoURL,$carpetaDocumentoID,$documentoUSRCREO,$documentoTIPO);
        }
         return $documento->documentoID;
    }
 }