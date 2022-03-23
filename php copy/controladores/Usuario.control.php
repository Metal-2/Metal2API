<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
class UsuarioControlador extends Controladores
{


    function validarUsuarioAdmin()
    {
        $usuarioNombre = $this->usuarioNombre;
        $usuarioClave = $this->usuarioClave;
        $info = isset($this->info) ? $this->info : NULL;
        $providerAuth = isset($this->providerAuth) ? $this->providerAuth : "LOCAL";
        $providerAuthData = isset($this->providerAuthData) ? $this->providerAuthData : NULL;
    
        $this->validarUsuario($usuarioNombre, $usuarioClave, $info, $providerAuth, $providerAuthData, true);
    }

    function validarUsuarioApp()
    {
        $usuarioNombre = $this->usuarioNombre;
        $usuarioClave = $this->usuarioClave;
        $info = isset($this->info) ? $this->info : NULL;
        $providerAuth = isset($this->providerAuth) ? $this->providerAuth : "LOCAL";
        $providerAuthData = isset($this->providerAuthData) ? $this->providerAuthData : NULL;
    
        $this->validarUsuario($usuarioNombre, $usuarioClave, $info, $providerAuth, $providerAuthData);
    }

    function validarUsuario($usuarioNombre, $usuarioClave, $info, $providerAuth, $providerAuthData, $validateUsuarioADMIN = false, $validateProviderAuthData = true)
    {
        
        $mensaje = "";
        if ($providerAuth != "LOCAL") {

            $usuario = Usuarios::hasUsuarioNombre($usuarioNombre);

            if (!$usuario && !$validateProviderAuthData) {
                $personaID = Personas::guardarBasico(
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    $providerAuthData->name,
                    $providerAuthData->email
                );
                $usuario =  Usuarios::guardarPorSistema($usuarioNombre, Usuarios::SISTEMA, $personaID);
            } else {
                $mensaje .= " o el usuario no tiene permitido ingresar";
            }
            Usuarios::actualizarAvatar($usuarioNombre, $providerAuthData->photoUrl);
        } else {
            $usuario = Usuarios::validarUsuario($usuarioNombre, $usuarioClave);
        }

        if ($usuario) {
            $isAdminOrSuperAdmin = (($usuario->usuarioTIPO == Usuarios::ADMIN)?true:false)?true: (($usuario->usuarioTIPO == Usuarios::SUPER_ADMIN)?true:false);
    
            if ($validateUsuarioADMIN && !$isAdminOrSuperAdmin) {
                echo Respuestas::info("El usuario no es administrador.");
                return;
            }

            if ($usuario->usuarioESTADO == Usuarios::ACTIVO) {

                $token = $this->generar_token_seguro(10);
                $usuario->providerAuth = $providerAuth;
                echo Respuestas::exito([
                    "USUARIO" => $usuario,
                    "TOKEN" => $token,
                ]);

                Usuarios::guardarActividadUsuario(
                    $token,
                    $usuario->usuarioID,
                    Usuarios::INGRESO,
                    $info,
                    $providerAuth,
                    json_encode($providerAuthData)
                );
            } else {
                echo Respuestas::info("El usuario se encuenta desactivo.");
            }
        } else {
            echo Respuestas::info("Los datos ingresados no son correctos" . $mensaje . ".");
        }
    }

    function generar_token_seguro($longitud)
    {

        if ($longitud < 4) {
            $longitud = 4;
        }

        return bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
    }

    function cerrarSesion()
    {
        $actividad = Usuarios::guardarActividadUsuario(
            $this->token,
            $this->usuarioID,
            Usuarios::CERRAR_SESION,
            $this->info
        );

        if ($actividad) {
            return  Respuestas::exito();
        }
        echo Respuestas::error("inconvenientes al cerrar sesion.");
    }

    function getAll()
    {
        $busqueda = isset($this->busqueda) ? $this->busqueda : NULL;
        $inicioBusqueda = isset($this->inicioBusqueda) ? $this->inicioBusqueda : NULL;
        $cantidad = isset($this->cantidad) ? $this->cantidad : NULL;
        $this->allFiltro(Usuarios::USER_NORMAL, $busqueda, $inicioBusqueda, $cantidad);
    }

    function getAllAdmin()
    {
        $busqueda = isset($this->busqueda) ? $this->busqueda : NULL;
        $inicioBusqueda = isset($this->inicioBusqueda) ? $this->inicioBusqueda : NULL;
        $cantidad = isset($this->cantidad) ? $this->cantidad : NULL;
        $this->allFiltro(Usuarios::ADMIN, $busqueda, $inicioBusqueda, $cantidad);
    }

    function allFiltro($usuarioTIPO, $busqueda, $inicioBusqueda, $cantidad)
    {
        if (is_null($inicioBusqueda) && is_null($busqueda)) {
            $usuarios = Usuarios::porRango(0, $cantidad, $usuarioTIPO);
            $cantidadTotal = Usuarios::cantidadTotal($usuarioTIPO);

            echo Respuestas::exito(['Datos' => $usuarios, 'CantidadTotal' => $cantidadTotal]);
        } else {
            if (empty($busqueda) || is_null($busqueda)) {
                $usuarios = Usuarios::porRango($inicioBusqueda, $cantidad, $usuarioTIPO);
            } else {
                $usuarios = Usuarios::porFiltroYRango($busqueda, $inicioBusqueda, $cantidad, $usuarioTIPO);
            }
            echo Respuestas::exito($usuarios);
        }
    }

    function datosCompletosUsuario()
    {
        $usuario = Usuarios::datosCompletos($this->usuarioID);
        if ($usuario) {
            echo Respuestas::exito("Usuario", $usuario);
        } else {
            echo Respuestas::error("Error al buscar los datos del usuario.");
        }
    }



    function guardar()
    {
        $this->crearUsuario();
    }

    function crearUsuario()
    {
        $personaID = Personas::gestionarPersona(
            $this->tipoPersonaID,
            $this->tipoIdentificacionID,
            $this->personaIDENTIFICACION,
            empty($this->personaPRIMERNOMBRE) ? NULL : $this->personaPRIMERNOMBRE,
            empty($this->personaSEGUNDONOMBRE) ? NULL : $this->personaSEGUNDONOMBRE,
            NULL,
            empty($this->personaPRIMERAPELLIDO) ? NULL : $this->personaPRIMERAPELLIDO,
            empty($this->personaSEGUINDOAPELLIDO) ? NULL : $this->personaSEGUINDOAPELLIDO,
            NULL,
            empty($this->personaRAZONSOCIAL) ? NULL : $this->personaRAZONSOCIAL,
            $this->personaCORREO,
            $this->personaCELULAR,
            empty($this->nivelEscolarID) ? NULL : $this->nivelEscolarID,
            $this->usuarioUSR,
            empty($this->personaFCHNACIMIENTO) ? NULL : $this->personaFCHNACIMIENTO,
            empty($this->personaDIRECCION) ? NULL : $this->personaDIRECCION,
            empty($this->fondoPensionID) ? NULL : $this->fondoPensionID,
            empty($this->epsID) ? NULL : $this->epsID,
            empty($this->arlID) ? NULL : $this->arlID,
            empty($this->personaNUMEROHIJOS) ? NULL : $this->personaNUMEROHIJOS,
            empty($this->personaMUNICIPIONACIMIENTO) ? NULL : $this->personaMUNICIPIONACIMIENTO,
            empty($this->personaGENERO) ? NULL : $this->personaGENERO,
            empty($this->personaESTADOCIVIL) ? NULL : $this->personaESTADOCIVIL,
            empty($this->personaRUT) ? NULL : $this->personaRUT,
            empty($this->personaMUNICIPIOEXPEDICION) ? NULL : $this->personaMUNICIPIOEXPEDICION
        );
        if ($personaID) {
            $colaboradorID = Colaboradores::gestionar(
                $personaID,
                $this->grupoTrabajoID,
                NULL,
                $this->cargoID,
                $this->usuarioUSR,
                $this->colaboradorFCHINGRESO,
                $this->colaboradorCORREO
            );
            if ($colaboradorID) {
                $usuarioGuardado = Usuarios::gestionar(
                    $colaboradorID,
                    $this->usuarioNOMBRE,
                    $this->usuarioUSR,
                    $this->usuarioTIPO,
                    $this->usuarioESTADO,
                    empty($this->usuarioCLAVE) ? NULL : $this->usuarioCLAVE
                );
                if ($usuarioGuardado) {
                    echo Respuestas::exito("Operación exitosa.");
                } else {
                    echo Respuestas::error("Error al crear el usuario, verifique el nombre de usuario no este registrado.");
                }
            } else {
                echo Respuestas::error("Error al crear el colaborador.");
            }
        } else {
            echo Respuestas::error("Error al insertar los datos de la persona");
        }
    }


    function getAdminAppMenu()
    {
        $usuarioID=$this->usuarioID;
        $usuario=Usuarios::dato($usuarioID);
        if($usuario){
            $items=[];
            $menuComponentes=ControlOperaciones::todosComponentes();

            $items= ControlOperaciones::menuDelUsuario($usuarioID);
           
            foreach($menuComponentes as $componente){
                $componente->items=[];
                foreach($items as $item){
                    if($item->controlComponenteID==$componente->controlComponenteID){
                        array_push($componente->items,$item);
                        $indice = array_search($item,$items,false);
                        array_splice($items,$indice,1);
                        /*unset($items[$index]);*/
                    }
                }
            }
            echo Respuestas::exito("item menus",["Components"=>$menuComponentes,"Items"=>$items]);
        }else{
            echo Respuestas::error("Usuario no existe.");
        }
    }

    function operacionesUsuario()
    {
        $todosOperaciones = ControlOperaciones::todosOperaciones();
        $usuarioOperaciones = ControlOperaciones::todosOperacionesDelUusario($this->usuarioID);
        $usuario = Usuarios::datosCompletos($this->usuarioID);
        echo Respuestas::exito("operaciones.", ["TodosOperaciones" => $todosOperaciones, "UsuarioOperaciones" => $usuarioOperaciones, "Usuario" => $usuario]);
    }

    function guardarOperacionesUsuario()
    {
        $arrayOperaciones = $this->operaciones;
        $usuarioID = $this->usuarioID;
        $usuarioOperaciones = ControlOperaciones::todosOperacionesDelUusario($usuarioID);
        $contadorEliminados = 0;
        foreach ($usuarioOperaciones as $operacion) {
            $index = array_search($operacion->controlOperacionID, $arrayOperaciones);
            if ($index === false) {
                ControlOperaciones::eliminarOperacionUsuario($usuarioID, $operacion->controlOperacionID);
                $contadorEliminados++;
            } else {
                unset($arrayOperaciones[$index]);
            }
        }
        $numOperaciones = count($arrayOperaciones);
        if ($numOperaciones) {
            $contador = 0;
            foreach ($arrayOperaciones as $value) {
                $guadado = ControlOperaciones::guardarOperacionUsuario($usuarioID, $value);
                if ($guadado) {
                    $contador++;
                }
            }
            if ($numOperaciones == $contador) {
                echo Respuestas::exito("Permisos guardados.");
            } else {
                echo Respuestas::error("No se pudo guardar los cambios.");
            }
        } else {
            if ($contadorEliminados > 0) {
                echo Respuestas::exito("Se eliminaron los permisos.");
            } else {
                echo Respuestas::info("No se realizaron cambios.");
            }
        }
    }

    function importarUsuariosExcel(
        $tipoPersonaID,
        $tipoIdentificacionID,
        $personaIDENTIFICACION,
        $personaPRIMERNOMBRE,
        $personaSEGUNDONOMBRE,
        $personaPRIMERAPELLIDO,
        $personaSEGUINDOAPELLIDO,
        $personaCORREO,
        $personaCELULAR,
        $nivelEscolarID,
        $usuarioUSR,
        $personaFCHNACIMIENTO,
        $personaDIRECCION,
        $fondoPensionID,
        $epsID,
        $arlID,
        $personaNUMEROHIJOS,
        $personaMUNICIPIONACIMIENTO,
        $personaGENERO,
        $personaESTADOCIVIL,
        $personaRUT,

        $grupoTrabajoID,
        $cargoID,
        $colaboradorFCHINGRESO,
        $colaboradorCORREO,


        $usuarioNOMBRE,
        $usuarioTIPO,
        $usuarioESTADO,
        $usuarioCLAVE


    ) {
        $personaID = Personas::gestionarPersona(
            $tipoPersonaID,
            $tipoIdentificacionID,
            $personaIDENTIFICACION,
            $personaPRIMERNOMBRE,
            $personaSEGUNDONOMBRE,
            NULL,
            $personaPRIMERAPELLIDO,
            $personaSEGUINDOAPELLIDO,
            NULL,
            NULL,
            $personaCORREO,
            $personaCELULAR,
            $nivelEscolarID,
            $usuarioUSR,
            $personaFCHNACIMIENTO,
            $personaDIRECCION,
            $fondoPensionID,
            $epsID,
            $arlID,
            $personaNUMEROHIJOS,
            $personaMUNICIPIONACIMIENTO,
            $personaGENERO,
            $personaESTADOCIVIL,
            $personaRUT
        );
        if ($personaID) {
            $colaboradorID = Colaboradores::gestionar(
                $personaID,
                $grupoTrabajoID,
                NULL,
                $cargoID,
                $usuarioUSR,
                $colaboradorFCHINGRESO,
                $colaboradorCORREO
            );
            if ($colaboradorID) {
                $usuarioGuardado = Usuarios::gestionar(
                    $colaboradorID,
                    $usuarioNOMBRE,
                    $usuarioUSR,
                    $usuarioTIPO,
                    $usuarioESTADO,
                    $usuarioCLAVE
                );
                if ($usuarioGuardado) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    function importarExcel()
    {
        //$file=empty($this->archivo)?NULL:$this->archivo;
        //if(isset($file)){
        $rutaArchivo = "INTRANET_ARCHIVOS/UTILIDADES/importarUsuario.xlsx";
        //move_uploaded_file($file['tmp_name'], $rutCarpeta);

        if (file_exists($rutaArchivo)) {
            try {
                $info = new SplFileInfo($rutaArchivo);
                $lector = IOFactory::createReader(ucwords($info->getExtension()));
                if ($info->getExtension() == "csv") {
                    $lector->setInputEncoding('CP1252');
                    $lector->setDelimiter(";");
                }
                $spreadsheet = $lector->load($rutaArchivo);
                $arrayExcel = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $count = 0;
                foreach ($arrayExcel as $fila => $usuario) {
                    if ($fila > 1) {
                        if ($usuario["C"]) {
                            $guardado = $this->importarUsuariosExcel(
                                $usuario["A"],
                                $usuario["B"],
                                $usuario["C"],
                                $usuario["D"],
                                $usuario["E"],
                                $usuario["F"],
                                $usuario["G"],
                                $usuario["K"],
                                $usuario["L"],
                                $usuario["Z"],
                                1,
                                NULL,
                                $usuario["M"],
                                NULL,
                                NULL,
                                NULL,
                                $usuario["I"],
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                $usuario["V"],
                                $usuario["AA"],
                                "NORMAL",
                                "ACTIVO",
                                $usuario["C"]
                            );

                            if ($guardado) {
                                $count++;
                            }
                        }
                    }
                }
                echo Respuestas::exito($count . "usuarios guardados de " . count($arrayExcel));
            } catch (\Throwable $th) {
                echo Respuestas::error($th);
            }
        } else {
            echo Respuestas::error("Archivo no existe.");
        }
        //}else{
        //  echo Respuestas::error("Error al subir el documento.");
        //}
    }

    function enviarCorreo()
    {
        Email::send(
            [["email"=>"jhoropertuz@gmail.com", "name"=>"atan romero"]],
            "primera pruebaaaa",
            NULL,
            "VALIDATE_EMAIL",
            ["URL" => "http://localhost:4200/#/maps"]
        );
    }
}
