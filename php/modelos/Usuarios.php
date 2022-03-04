<?php
class Usuarios
{

    const ACTIVO = "ACTIVO";
    const DESACTIVO = "DESACTIVO";
    const INGRESO = "INGRESO";
    const CERRAR_SESION = "CERRAR SESION";

    const SISTEMA="SISTEMA";
    const USUARIO = "USUARIO";

    const DATOS_BASICOS = <<<EOD
  SELECT
     `usuarios`.* FROM
  		`usuarios` 
EOD;

    const DATOS_COMPLETOS = <<<EOD
    SELECT
        usuarios.usuarioID,
        usuarios.usuarioNOMBRE,
        usuarios.usuarioUSRCREO,
        usuarios.usuarioUSRMODIFICO,
        usuarios.usuarioFCHCREO,
        usuarios.usuarioFCHMODIFICO,
        usuarios.usuarioTIPO,
        usuarios.usuarioESTADO,
        usuarios.usuarioAVATAR,
        personas.*,
        tipospersonas.*,
        tiposidentificaciones.*,
        nivelesescolares.*,
        entidadespromotorassalud.*,
        fondospensiones.*,
        aseguradorasriesgoslaborales.*,
        municipioNACIMIENTO.*,
        deartamentoNACIMIENTO.* ,
        municipioEXPEDICION.municipioID as municipioExpedicionID,
        deartamentoEXPEDICION.departamentoID as departamentoExpedicionID,
        paisesEXPEDICION.paisID as paiseExpedicionID
    FROM
        `usuarios`
    LEFT JOIN `personas` 
        ON usuarios.personaID = personas.personaID
    LEFT JOIN `tipospersonas` 
        ON personas.tipoPersonaID = tipospersonas.tipoPersonaID
    LEFT JOIN `tiposidentificaciones` 
        ON personas.tipoIdentificacionID = tiposidentificaciones.tipoIdentificacionID
    LEFT JOIN `nivelesescolares` 
        ON personas.nivelEscolarID = nivelesescolares.nivelEscolarID
    LEFT JOIN `entidadespromotorassalud` 
        ON personas.epsID = entidadespromotorassalud.epsID
    LEFT JOIN `fondospensiones` 
        ON personas.fondoPensionID = fondospensiones.fondoPensionID
    LEFT JOIN `aseguradorasriesgoslaborales` 
        ON personas.arlID = aseguradorasriesgoslaborales.arlID
    LEFT JOIN `municipios` as municipioNACIMIENTO
        ON personas.personaMUNICIPIONACIMIENTO = municipioNACIMIENTO.municipioID
    LEFT JOIN `departamentos` as deartamentoNACIMIENTO
        ON municipioNACIMIENTO.departamentoID = deartamentoNACIMIENTO.departamentoID
    LEFT JOIN `municipios` as municipioEXPEDICION
        ON personas.personaMUNICIPIOEXPEDICION = municipioEXPEDICION.municipioID
    LEFT JOIN `departamentos` as deartamentoEXPEDICION
        ON municipioEXPEDICION.departamentoID = deartamentoEXPEDICION.departamentoID
    LEFT JOIN `paises` as paisesEXPEDICION
        ON deartamentoEXPEDICION.paisID = paisesEXPEDICION.paisID
EOD;

    public static function validarUsuario($usuarioNombre, $usuarioClave)
    {
        $sql = self::DATOS_COMPLETOS . " WHERE usuarioNOMBRE = ? AND  usuarioCLAVE = MD5(?)";
        return Conexion::selectUnaFila($sql, [$usuarioNombre, $usuarioClave]);
    }

    public static function hasUsuarioNombre($usuarioNombre)
    {
        $sql = self::DATOS_COMPLETOS . " WHERE usuarioNOMBRE = ?";
        return Conexion::selectUnaFila($sql, [$usuarioNombre]);
    }

    public static function guardarActividadUsuario($token,$usuarioID,$tipo,$info=NULL,$actividadUsuarioPROVIDERAUTH=NULL, $actividadUsuarioPROVIDERAUTHDATA=NULL)
    {
        $sql = <<<EOD
        INSERT INTO  `actividadUsuario` (
            actividadUsuarioTOKEN,
            usuarioID,
            actividadUsuarioINFO,
            actividadUsuarioTIPO,
            actividadUsuarioPROVIDERAUTH,
            actividadUsuarioPROVIDERAUTHDATA
            )
        VALUES (?,?,?,?,?,?)
EOD;
        return Conexion::insertFila($sql, [$token,$usuarioID,$info,$tipo,$actividadUsuarioPROVIDERAUTH,$actividadUsuarioPROVIDERAUTHDATA]);
    }
    
    public static function cantidadTotal()
    {
        $sql = <<<EOD
        SELECT
        	COUNT(*) as cantidadTotal
        FROM
            `usuarios` 
EOD;
        return Conexion::selectUnaFila($sql);
    }
    
    public static function dato($usuarioID)
    {
        $sql = <<<EOD
        SELECT
        `usuarios`.*
        FROM
            `usuarios` 
        WHERE usuarioID = ?
EOD;
        return Conexion::selectUnaFila($sql, [$usuarioID]);
    }
    public  static function todos()
    {
        $sql = <<<EOD
        SELECT
        `usuarios`.*
        FROM
            `usuarios`
            
EOD;
        return Conexion::selectVariasFilas($sql);
    }

    public  static function datosCompletos($usuarioID)
    {
        $sql = self::DATOS_COMPLETOS . ' WHERE usuarios.usuarioID=? ';
        return Conexion::selectUnaFila($sql, [$usuarioID]);
    }


    public static function porRango($inicioBusqueda, $cantidad)
    {
        $sql = self::DATOS_COMPLETOS;
        $sql .= " LIMIT $inicioBusqueda,$cantidad ";

        return Conexion::selectVariasFilas($sql);
    }

    public static function porFiltroYRango($busqueda, $inicioBusqueda, $cantidad)
    {
        $sql = self::DATOS_COMPLETOS;
        $sql .= " WHERE usuarios.usuarioNOMBRE LIKE '%$busqueda%' OR personas.personaRAZONSOCIAL LIKE '%$busqueda%'  LIMIT $inicioBusqueda,$cantidad ";

        return Conexion::selectVariasFilas($sql);
    }

    public static function buscarPorNombre($usuarioNOMBRE)
    {
        $sql = "SELECT `usuarios`.* FROM `usuarios` WHERE usuarioNOMBRE = ?";
        return Conexion::selectUnaFila($sql, [$usuarioNOMBRE]);
    }

    public static function buscarPorColaboradorID($colaboradorID)
    {
        $sql = "SELECT `usuarios`.* FROM `usuarios` WHERE colaboradorID = ?";
        return Conexion::selectUnaFila($sql, [$colaboradorID]);
    }


    public static function guardar($colaboradorID, $usuarioNOMBRE, $usuarioCLAVE, $usuarioUSR, $usuarioTIPO = "NORMAL", $usuarioESTADO = "ACTIVO")
    {

        $sql = <<<EOD
        INSERT INTO  `usuarios` (
            usuarioNOMBRE, 
            usuarioCLAVE,
            colaboradorID, 
            usuarioTIPO,
            usuarioESTADO,
            usuarioUSRCREO,
            usuarioTOKEN
            )
        VALUES (?,?,?,?,?,?,?)
EOD;
        return Conexion::insertFila($sql, [$usuarioNOMBRE, md5($usuarioCLAVE), $colaboradorID, $usuarioTIPO, $usuarioESTADO, $usuarioUSR, uniqid()]);
    }

    public static function guardarPorSistema($usuarioNOMBRE,$usuarioCREADORPOR,$personaID, $usuarioTIPO = "NORMAL", $usuarioESTADO = "ACTIVO")
    {

        $sql = <<<EOD
        INSERT INTO  `usuarios` (
            usuarioNOMBRE, 
            usuarioTIPO,
            usuarioESTADO,
            usuarioCREADORPOR,
            personaID
            )
        VALUES (?,?,?,?,?)
EOD;
  
        $usuario = Conexion::insertFila($sql, [$usuarioNOMBRE, $usuarioTIPO, $usuarioESTADO,$usuarioCREADORPOR, $personaID]);
        if ($usuario) {
            return self::hasUsuarioNombre($usuarioNOMBRE);
        }
        return false;
    }

    public static function actualizar($usuarioNOMBRE, $usuarioUSR, $usuarioTIPO, $usuarioESTADO, $usuarioID)
    {
        $sql = <<<EOD
    UPDATE `usuarios` SET
        usuarioNOMBRE=?,
        usuarioUSRMODIFICO=?,
        usuarioTIPO=?,
        usuarioESTADO=?
        WHERE usuarioID = ?
EOD;
        return Conexion::actualizarFila($sql, [$usuarioNOMBRE, $usuarioUSR, $usuarioTIPO, $usuarioESTADO, $usuarioID]);
    }

    public static function actualizarClave($usuarioCLAVE, $usuarioID)
    {
        $sql = <<<EOD
        UPDATE `usuarios` SET
            usuarioCLAVE = ?
            WHERE usuarioID = ?
EOD;
        return Conexion::actualizarFila($sql, [md5($usuarioCLAVE), $usuarioID]);
    }

    public static function actualizarAvatar($usuarioNombre, $photoUrl)
    {
        $sql = <<<EOD
    UPDATE `usuarios` SET
        usuarioAVATAR=?
        WHERE usuarioNOMBRE = ?
EOD;
        return Conexion::actualizarFila($sql, [$photoUrl, $usuarioNombre]);
    }

    public static function gestionar($colaboradorID, $usuarioNOMBRE, $usuarioUSR, $usuarioTIPO = "NORMAL", $usuarioESTADO = "ACTIVO", $usuarioCLAVE = NULL)
    {
        $busqueda = self::buscarPorColaboradorID($colaboradorID);
        if (!self::buscarPorNombre($usuarioNOMBRE) || $busqueda->usuarioNOMBRE == $usuarioNOMBRE) {
            if ($busqueda) {
                self::actualizar($usuarioNOMBRE, $usuarioUSR, $usuarioTIPO, $usuarioESTADO, $busqueda->usuarioID);
                if (!is_null($usuarioCLAVE) && md5($usuarioCLAVE) != $busqueda->usuarioCLAVE) {
                    self::actualizarClave($usuarioCLAVE, $busqueda->usuarioID);
                }
                return $busqueda->usuarioID;
            } else {
                return self::guardar($colaboradorID, $usuarioNOMBRE, $usuarioCLAVE, $usuarioUSR, $usuarioTIPO, $usuarioESTADO);
            }
        }
        return false;
    }
}
