<?php 
 class MesaDeAyuda{

    const ENCUERSO = "ENCUERSO";
    const FINALIZADA = "FINALIZADA";
    const CERRADA = "CERRADA";
    const PENDIENTE = "PENDIENTE";

    const SELECT_COMPLETO = <<<EOD
            SELECT  
            personas.*,
            personasAsignada.personaRAZONSOCIAL AS personasAsignadaRAZONSOCIAL,
            colaboradores.*,
            grupostrabajos.*,
            contratos.*,
            cargos.*,
            tipospersonas.*,
            tiposidentificaciones.*,
            `mesadeayuda`.* FROM `intranet`.`mesadeayuda`
        LEFT JOIN `intranet`.`usuarios` 
            ON mesadeayuda.mesaDeAyudaUSUARIOCREO = usuarios.usuarioID
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
        LEFT JOIN `intranet`.colaboradores AS colaboradoresAsignado
            ON mesadeayuda.mesaDeAyudaCOLABORADORASIGNADO = colaboradoresAsignado.colaboradorID
        LEFT JOIN `intranet`.personas AS personasAsignada
            ON colaboradoresAsignado.personaID = personasAsignada.personaID
EOD;
    
    public static function buscarPorUsuarioID($usuarioID){
        $sql=self::SELECT_COMPLETO . " WHERE mesaDeAyudaUSUARIOCREO=? ORDER BY mesaDeAyudaID DESC";
        return Conexion::selectvariasFilas($sql,[$usuarioID]);
    }

    public static function buscarPorEstado($estado){
        $sql=self::SELECT_COMPLETO . " WHERE mesaDeAyudaESTADO=? ORDER BY mesaDeAyudaID DESC";
        return Conexion::selectvariasFilas($sql,[$estado]);
    }

    public static function buscarPorId($mesaDeAyudaID){
        $sql=self::SELECT_COMPLETO . " WHERE mesaDeAyudaID=? ";
        return Conexion::selectUnaFila($sql,[$mesaDeAyudaID]);
    }

    public static function todo(){
        $sql=self::SELECT_COMPLETO . " ORDER BY mesaDeAyudaID DESC" ;
        return Conexion::selectvariasFilas($sql);
    }

    public static function guardar(
        $usuarioID,
        $mesaDeAyudaNIVELPRIORIDAD,
        $mesaDeAyudaDESCRIPCION,
        $mesaDeAyudaNUMEROINVENTARIOEQUIPO,
        $mesaDeAyudaUBICACIONDELSUCESO,
        $mesaDeAyudaTIPOSOLICITUD 
    ){
        $sql=<<<EOD
            INSERT INTO  `intranet`.`mesadeayuda` (
                mesaDeAyudaUSUARIOCREO, 
                mesaDeAyudaNIVELPRIORIDAD, 
                mesaDeAyudaDESCRIPCION,
                mesaDeAyudaNUMEROINVENTARIOEQUIPO,
                mesaDeAyudaUBICACIONDELSUCESO,
                mesaDeAyudaTIPOSOLICITUD
                )
            VALUES (?,?,?,?,?,?)
EOD;
        return Conexion::insertFila($sql,[
            $usuarioID,
            $mesaDeAyudaNIVELPRIORIDAD,
            $mesaDeAyudaDESCRIPCION,
            $mesaDeAyudaNUMEROINVENTARIOEQUIPO,
            $mesaDeAyudaUBICACIONDELSUCESO,
            $mesaDeAyudaTIPOSOLICITUD 
        ]);
    }

    public static function actualizar(
        $mesaDeAyudaID,
        $mesaDeAyudaNIVELPRIORIDAD,
        $mesaDeAyudaDESCRIPCION,
        $mesaDeAyudaNUMEROINVENTARIOEQUIPO,
        $mesaDeAyudaUBICACIONDELSUCESO,
        $mesaDeAyudaTIPOSOLICITUD
    ){
        $sql=<<<EOD
            UPDATE `intranet`.`mesadeayuda` SET
            mesaDeAyudaNIVELPRIORIDAD=?,
            mesaDeAyudaDESCRIPCION=?,
            mesaDeAyudaNUMEROINVENTARIOEQUIPO=?,
            mesaDeAyudaUBICACIONDELSUCESO=?,
            mesaDeAyudaTIPOSOLICITUD=?
            WHERE mesaDeAyudaID = ?
EOD;
        return Conexion::actualizarFila($sql,[
            $mesaDeAyudaNIVELPRIORIDAD,
            $mesaDeAyudaDESCRIPCION,
            $mesaDeAyudaNUMEROINVENTARIOEQUIPO,
            $mesaDeAyudaUBICACIONDELSUCESO,
            $mesaDeAyudaTIPOSOLICITUD,
            $mesaDeAyudaID
        ]);
    }

    public static function actualizarGestion(
        $mesaDeAyudaID,
        $mesaDeAyudaNIVELPRIORIDAD,
        $mesaDeAyudaDESCRIPCION,
        $mesaDeAyudaNUMEROINVENTARIOEQUIPO,
        $mesaDeAyudaUBICACIONDELSUCESO,
        $mesaDeAyudaTIPOSOLICITUD,
        $mesaDeAyudaCOLABORADORASIGNADO,
        $mesaDeAyudaESTADO,
        $mesaDeAyudaNOTA,
        $mesaDeAyudaUSUARIOMODIFICO
    ){
        $fechaActua=date("Y-m-d H:i:s");

        $mesaDeAyuda = self::buscarPorId($mesaDeAyudaID);

        $mesaDeAyudaFCHFINALIZACION = $mesaDeAyuda->mesaDeAyudaFCHFINALIZACION;
        $mesaDeAyudaFCHCERRADO = $mesaDeAyuda->mesaDeAyudaFCHCERRADO;
        $mesaDeAyudaFCHENCURSO = $mesaDeAyuda->mesaDeAyudaFCHENCURSO;

        if (!$mesaDeAyuda->mesaDeAyudaCOLABORADORASIGNADO && $mesaDeAyudaCOLABORADORASIGNADO) {
            $mesaDeAyudaFCHASIGNACION = $fechaActua;
        }else{
            $mesaDeAyudaFCHASIGNACION = $mesaDeAyuda->mesaDeAyudaFCHASIGNACION;
        }

        switch ($mesaDeAyudaESTADO) {
            case self::ENCUERSO:
                $mesaDeAyudaFCHENCURSO=$fechaActua;
                break;
            case self::FINALIZADA:
                $mesaDeAyudaFCHFINALIZACION=$fechaActua;
                break;
            case self::CERRADA:
                $mesaDeAyudaFCHCERRADO=$fechaActua;
                break;
        }

        $sql=<<<EOD
            UPDATE `intranet`.`mesadeayuda` SET
            mesaDeAyudaNIVELPRIORIDAD=?,
            mesaDeAyudaDESCRIPCION=?,
            mesaDeAyudaNUMEROINVENTARIOEQUIPO=?,
            mesaDeAyudaUBICACIONDELSUCESO=?,
            mesaDeAyudaTIPOSOLICITUD=?,
            mesaDeAyudaCOLABORADORASIGNADO=?,
            mesaDeAyudaNOTA=?,
            mesaDeAyudaUSUARIOMODIFICO=?,
            mesaDeAyudaESTADO=?,
            mesaDeAyudaFCHFINALIZACION=?,
            mesaDeAyudaFCHCERRADO=?,
            mesaDeAyudaFCHENCURSO =?,
            mesaDeAyudaFCHASIGNACION = ?
            WHERE mesaDeAyudaID = ?
EOD;
        return Conexion::actualizarFila($sql,[
            $mesaDeAyudaNIVELPRIORIDAD,
            $mesaDeAyudaDESCRIPCION,
            $mesaDeAyudaNUMEROINVENTARIOEQUIPO,
            $mesaDeAyudaUBICACIONDELSUCESO,
            $mesaDeAyudaTIPOSOLICITUD,
            $mesaDeAyudaCOLABORADORASIGNADO,
            $mesaDeAyudaNOTA,
            $mesaDeAyudaUSUARIOMODIFICO,
            $mesaDeAyudaESTADO,
            $mesaDeAyudaFCHFINALIZACION, 
            $mesaDeAyudaFCHCERRADO,
            $mesaDeAyudaFCHENCURSO,
            $mesaDeAyudaFCHASIGNACION,
            $mesaDeAyudaID
        ]);
    }

 }