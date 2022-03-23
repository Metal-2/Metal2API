<?php 
 class Colaboradores{
    
    const SELECT_BASICO = <<<EOD
            SELECT  `colaboradores`.* FROM `intranet`.`colaboradores` 
EOD;
    
    const SELECT_MEDIO = <<<EOD
    SELECT
        personas.*,
        colaboradores.*
    FROM
        `intranet`.`usuarios`
    LEFT JOIN `intranet`.`colaboradores` 
        ON usuarios.colaboradorID = colaboradores.colaboradorID
    LEFT JOIN `intranet`.`personas` 
        ON colaboradores.personaID = personas.personaID
EOD;

    const SELECT_COMPLETO = <<<EOD
    SELECT
        usuarios.usuarioTIPO,
        usuarios.usuarioESTADO,
        personas.*,
        colaboradores.*,
        grupostrabajos.*,
        contratos.*,
        cargos.*,
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
        `intranet`.`usuarios`
    LEFT JOIN `intranet`.`colaboradores` 
        ON usuarios.colaboradorID = colaboradores.colaboradorID
    LEFT JOIN `intranet`.`personas` 
        ON colaboradores.personaID = personas.personaID
    LEFT JOIN `intranet`.`grupostrabajos` 
        ON colaboradores.grupoTrabajoID = grupostrabajos.grupoTrabajoID
    LEFT JOIN `intranet`.`contratos` 
        ON colaboradores.contratoID = contratos.contratoID
    LEFT JOIN `intranet`.`cargos` 
        ON colaboradores.cargoID = cargos.cargoID
    LEFT JOIN `intranet`.`tipospersonas` 
        ON personas.tipoPersonaID = tipospersonas.tipoPersonaID
    LEFT JOIN `intranet`.`tiposidentificaciones` 
        ON personas.tipoIdentificacionID = tiposidentificaciones.tipoIdentificacionID
    LEFT JOIN `intranet`.`nivelesescolares` 
        ON personas.nivelEscolarID = nivelesescolares.nivelEscolarID
    LEFT JOIN `intranet`.`entidadespromotorassalud` 
        ON personas.epsID = entidadespromotorassalud.epsID
    LEFT JOIN `intranet`.`fondospensiones` 
        ON personas.fondoPensionID = fondospensiones.fondoPensionID
    LEFT JOIN `intranet`.`aseguradorasriesgoslaborales` 
        ON personas.arlID = aseguradorasriesgoslaborales.arlID
    LEFT JOIN `intranet`.`municipios` as municipioNACIMIENTO
        ON personas.personaMUNICIPIONACIMIENTO = municipioNACIMIENTO.municipioID
    LEFT JOIN `intranet`.`departamentos` as deartamentoNACIMIENTO
        ON municipioNACIMIENTO.departamentoID = deartamentoNACIMIENTO.departamentoID
    LEFT JOIN `intranet`.`municipios` as municipioEXPEDICION
        ON personas.personaMUNICIPIOEXPEDICION = municipioEXPEDICION.municipioID
    LEFT JOIN `intranet`.`departamentos` as deartamentoEXPEDICION
        ON municipioEXPEDICION.departamentoID = deartamentoEXPEDICION.departamentoID
    LEFT JOIN `intranet`.`paises` as paisesEXPEDICION
        ON deartamentoEXPEDICION.paisID = paisesEXPEDICION.paisID
EOD;

    
    public static function buscarPorPersosonaID($personaID){
        $sql=self::SELECT_BASICO . "WHERE personaID=? ";
        return Conexion::selectUnaFila($sql,[$personaID]);
    }

    public static function porIdentificacion($identificacion){
        $sql=self::SELECT_COMPLETO . " WHERE personaIDENTIFICACION = ? ";
        return Conexion::selectUnaFila($sql,[$identificacion]);
    }

    public static function porGrupoDeTrabajo($grupoTrabajoID){
        $sql=self::SELECT_MEDIO . " WHERE grupoTrabajoID = ? ";
        return Conexion::selectVariasFilas($sql,[$grupoTrabajoID]);
    }

    public static function guardar($personaID,$grupoTrabajoID,$contratoID,$cargoID,$usuarioUSR,$colaboradorFCHINGRESO,$colaboradorCORREO){
        $sql=<<<EOD
            INSERT INTO  `intranet`.`colaboradores` (
                personaID, 
                grupoTrabajoID, 
                contratoID,
                cargoID,
                colaboradorUSRCREO,
                colaboradorFCHINGRESO,
                colaboradorCORREO
                )
            VALUES (?,?,?,?,?,?,?)
EOD;
        return Conexion::insertFila($sql,[$personaID,$grupoTrabajoID,$contratoID,$cargoID,$usuarioUSR,$colaboradorFCHINGRESO,$colaboradorCORREO]);
    }

    public static function actualizar($personaID,$grupoTrabajoID,$contratoID,$cargoID,$usuarioUSR,$colaboradorFCHINGRESO,$colaboradorCORREO,$colaboradorID){
        $sql=<<<EOD
            UPDATE `intranet`.`colaboradores` SET
                personaID=?,
                grupoTrabajoID=?,
                contratoID=?,
                cargoID=?,
                colaboradorUSRMODIFICO=?,
                colaboradorFCHINGRESO=?,
                colaboradorCORREO=?
            WHERE colaboradorID = ?
EOD;
        return Conexion::actualizarFila($sql,[$personaID,$grupoTrabajoID,$contratoID,$cargoID,$usuarioUSR,$colaboradorFCHINGRESO,$colaboradorCORREO,$colaboradorID]);
    }

    public static function gestionar($personaID,$grupoTrabajoID,$contratoID,$cargoID,$usuarioUSR,$colaboradorFCHINGRESO,$colaboradorCORREO){
        $busqueda=self::buscarPorPersosonaID($personaID);
        if($busqueda){
            $personaID=is_null($personaID)?$busqueda->personaID:$personaID;
            $grupoTrabajoID=is_null($grupoTrabajoID)?$busqueda->grupoTrabajoID:$grupoTrabajoID;
            $contratoID=is_null($contratoID)?$busqueda->contratoID:$contratoID;
            $cargoID=is_null($cargoID)?$busqueda->cargoID:$cargoID;
            $colaboradorFCHINGRESO=is_null($colaboradorFCHINGRESO)?$busqueda->colaboradorFCHINGRESO:$colaboradorFCHINGRESO;
            $colaboradorCORREO=is_null($colaboradorCORREO)?$busqueda->colaboradorCORREO:$colaboradorCORREO;
            self::actualizar($personaID,$grupoTrabajoID,$contratoID,$cargoID,$usuarioUSR,$colaboradorFCHINGRESO,$colaboradorCORREO,$busqueda->colaboradorID);
            return $busqueda->colaboradorID;
        }else{
            return self::guardar($personaID,$grupoTrabajoID,$contratoID,$cargoID,$usuarioUSR,$colaboradorFCHINGRESO,$colaboradorCORREO);
        }

    }
 }