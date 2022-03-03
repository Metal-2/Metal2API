<?php 
 class ActividadesMedicas{

    const SELECT_BASICO_PACIENTES = <<<EOD
            SELECT  `actividadesMedicasPaciente`.* FROM `intranet`.`actividadesMedicasPaciente` 
EOD;

    const SELECT_COMPLETO_PACIENTES = <<<EOD
    SELECT  
        `actividadesMedicasPaciente`.*,
        servicios.*,
        especialidadesMedicas.*,
        personas.*,
        entidadespromotorassalud.*

    FROM `intranet`.`actividadesMedicasPaciente` 
    
    LEFT JOIN `intranet`.`personas` 
        ON actividadesMedicasPaciente.personaID = personas.personaID
    LEFT JOIN `intranet`.`servicios` 
        ON actividadesMedicasPaciente.servicioID = servicios.servicioID
    LEFT JOIN `intranet`.`especialidadesMedicas` 
        ON actividadesMedicasPaciente.especialidadMedicaID = especialidadesMedicas.especialidadMedicaID
    LEFT JOIN `intranet`.`entidadespromotorassalud` 
        ON entidadespromotorassalud.epsID = personas.epsID
EOD;

    const SELECT_COMPLETO = <<<EOD
    SELECT  `actividadesMedicasColaborador`.* ,
        colaboradores.*,
        personas.*,
        personaEntrega.personaRAZONSOCIAL as personaRAZONSOCIALENTREGA
        FROM `intranet`.`actividadesMedicasColaborador` 
        LEFT JOIN `intranet`.`colaboradores` 
            ON actividadesMedicasColaborador.actividadMedicaColaboradorASIGNACION = colaboradores.colaboradorID
        LEFT JOIN `intranet`.`personas` 
            ON colaboradores.personaID = personas.personaID
        LEFT JOIN `intranet`.`usuarios` 
            ON actividadesMedicasColaborador.usuarioID = usuarios.usuarioID
        LEFT JOIN `intranet`.`colaboradores` as colaboradorEntrega
            ON usuarios.colaboradorID = colaboradorEntrega.colaboradorID
        LEFT JOIN `intranet`.`personas` as personaEntrega
            ON colaboradorEntrega.personaID = personaEntrega.personaID
EOD;
    
    public static function guardar($usuarioID, $actividadMedicaColaboradorASIGNACION){
        $sql=<<<EOD
            INSERT INTO  `intranet`.`actividadesMedicasColaborador` (
                usuarioID,
                actividadMedicaColaboradorASIGNACION
                )
            VALUES (?,?)
EOD;

        return Conexion::insertFila($sql,[$usuarioID, $actividadMedicaColaboradorASIGNACION ]);
    }

    public static function actualizar($actividadMedicaColaboradorID, $actividadMedicaColaboradorASIGNACION){
        $sql=<<<EOD
            UPDATE `intranet`.`actividadesMedicasColaborador` SET
                actividadMedicaColaboradorASIGNACION=?
                WHERE actividadMedicaColaboradorID = ?
EOD;
        return Conexion::actualizarFila($sql,[$actividadMedicaColaboradorASIGNACION,$actividadMedicaColaboradorID]);
    }

    public static function buscarPacientePorActividadMedicaID($actividadMedicaID, $personaID){
        $sql=self::SELECT_BASICO_PACIENTES ;
        $sql .= " WHERE actividadMedicaColaboradorID=? AND personaID=?";
        return Conexion::selectUnaFila($sql,[$actividadMedicaID, $personaID]);
    }

    public static function buscarPorUsuarioID($usuarioID){
        $sql=self::SELECT_COMPLETO;
        $sql .= " WHERE actividadesMedicasColaborador.usuarioID=? order by actividadMedicaColaboradorID desc ";
        return Conexion::selectVariasFilas($sql,[$usuarioID]);
    }

    public static function buscarActividadMedicaColaboradorASIGNACION($colaboradorID){
        $sql=self::SELECT_COMPLETO;
        $sql .= " WHERE actividadMedicaColaboradorASIGNACION=? ";
        return Conexion::selectVariasFilas($sql,[$colaboradorID]);
    }

    public static function buscarPorActividadMedicaColaboradorID($actividadMedicaColaboradorID){
        $sql=self::SELECT_COMPLETO;
        $sql .= " WHERE actividadMedicaColaboradorID=? ";
        return Conexion::selectUnaFila($sql,[$actividadMedicaColaboradorID]);
    }

    public static function buscarTodosPacientesPorActividadMedicaID($actividadMedicaID){
        $sql=self::SELECT_COMPLETO_PACIENTES ;
        $sql .= " WHERE actividadMedicaColaboradorID=? ";
        return Conexion::selectVariasFilas($sql,[$actividadMedicaID]);
    }

    public static function gestionarPaciente(
        $actividadMedicaID,
        $personaID,
        $especialidadMedicaID,
        $actividadMedicaPacienteALERGIA,
        $actividadMedicaPacienteMEDICAMENTO,
        $actividadMedicaPacienteNOTA,
        $actividadMedicaPacienteDIAGNOSTICO,
        $servicioID,
        $actividadMedicaPacienteCAMA,
        $actividadMedicaPacienteINGRESO,
        $actividadMedicaPacientePERFILINFECCIOSO,
        $actividadMedicaPacientePERFILTOXEMICO,
        $actividadMedicaPacienteIMAGENES,
        $actividadMedicaPacientePARACLINICO
    ){
        $busqueda=self::buscarPacientePorActividadMedicaID(
            $actividadMedicaID,
            $personaID
        );

        if($busqueda){
            $especialidadMedicaID=is_null($especialidadMedicaID)?$busqueda->especialidadMedicaID:$especialidadMedicaID;
            $actividadMedicaPacienteALERGIA=is_null($actividadMedicaPacienteALERGIA)?$busqueda->actividadMedicaPacienteALERGIA:$actividadMedicaPacienteALERGIA;
            $actividadMedicaPacienteMEDICAMENTO=is_null($actividadMedicaPacienteMEDICAMENTO)?$busqueda->actividadMedicaPacienteMEDICAMENTO:$actividadMedicaPacienteMEDICAMENTO ;
            $actividadMedicaPacienteNOTA=is_null($actividadMedicaPacienteNOTA)?$busqueda->actividadMedicaPacienteNOTA:$actividadMedicaPacienteNOTA;
            $actividadMedicaPacienteDIAGNOSTICO=is_null($actividadMedicaPacienteDIAGNOSTICO)?$busqueda->actividadMedicaPacienteDIAGNOSTICO:$actividadMedicaPacienteDIAGNOSTICO;
            $servicioID=is_null($servicioID)? $busqueda->servicioID:$servicioID;
            $actividadMedicaPacienteCAMA=is_null($actividadMedicaPacienteCAMA)? $busqueda->actividadMedicaPacienteCAMA:$actividadMedicaPacienteCAMA;
            $actividadMedicaPacienteINGRESO=is_null($actividadMedicaPacienteINGRESO)? $busqueda->actividadMedicaPacienteINGRESO:$actividadMedicaPacienteINGRESO;
            $actividadMedicaPacientePERFILINFECCIOSO=is_null($actividadMedicaPacientePERFILINFECCIOSO)? $busqueda->actividadMedicaPacientePERFILINFECCIOSO:$actividadMedicaPacientePERFILINFECCIOSO;
            $actividadMedicaPacientePERFILTOXEMICO=is_null($actividadMedicaPacientePERFILTOXEMICO)? $busqueda->actividadMedicaPacientePERFILTOXEMICO:$actividadMedicaPacientePERFILTOXEMICO;
            $actividadMedicaPacienteIMAGENES=is_null($actividadMedicaPacienteIMAGENES)? $busqueda->actividadMedicaPacienteIMAGENES:$actividadMedicaPacienteIMAGENES;
            $actividadMedicaPacientePARACLINICO=is_null($actividadMedicaPacientePARACLINICO)? $busqueda->actividadMedicaPacientePARACLINICO:$actividadMedicaPacientePARACLINICO;

            self::actualizarPaciente(
                $busqueda->actividadMedicaPacienteID,
                $especialidadMedicaID,
                $actividadMedicaPacienteALERGIA,
                $actividadMedicaPacienteMEDICAMENTO,
                $actividadMedicaPacienteNOTA,
                $actividadMedicaPacienteDIAGNOSTICO,
                $servicioID,
                $actividadMedicaPacienteCAMA,
                $actividadMedicaPacienteINGRESO,
                $actividadMedicaPacientePERFILINFECCIOSO,
                $actividadMedicaPacientePERFILTOXEMICO,
                $actividadMedicaPacienteIMAGENES,
                $actividadMedicaPacientePARACLINICO
            );

            return $busqueda->actividadMedicaPacienteID;
        }else{
            return self::guardarPaciente(
                $actividadMedicaID,
                $personaID,
                $especialidadMedicaID,
                $actividadMedicaPacienteALERGIA,
                $actividadMedicaPacienteMEDICAMENTO,
                $actividadMedicaPacienteNOTA,
                $actividadMedicaPacienteDIAGNOSTICO,
                $servicioID,
                $actividadMedicaPacienteCAMA,
                $actividadMedicaPacienteINGRESO,
                $actividadMedicaPacientePERFILINFECCIOSO,
                $actividadMedicaPacientePERFILTOXEMICO,
                $actividadMedicaPacienteIMAGENES,
                $actividadMedicaPacientePARACLINICO
            );
        }

    }

    public static function guardarPaciente(
        $actividadMedicaID,
        $personaID,
        $especialidadMedicaID,
        $actividadMedicaPacienteALERGIA,
        $actividadMedicaPacienteMEDICAMENTO,
        $actividadMedicaPacienteNOTA,
        $actividadMedicaPacienteDIAGNOSTICO,
        $servicioID,
        $actividadMedicaPacienteCAMA,
        $actividadMedicaPacienteINGRESO,
        $actividadMedicaPacientePERFILINFECCIOSO,
        $actividadMedicaPacientePERFILTOXEMICO,
        $actividadMedicaPacienteIMAGENES,
        $actividadMedicaPacientePARACLINICO
        ){
        $sql=<<<EOD
            INSERT INTO  `intranet`.`actividadesMedicasPaciente` (
                actividadMedicaColaboradorID,
                personaID,
                especialidadMedicaID,
                actividadMedicaPacienteALERGIA,
                actividadMedicaPacienteMEDICAMENTO,
                actividadMedicaPacienteNOTA,
                actividadMedicaPacienteDIAGNOSTICO,
                servicioID,
                actividadMedicaPacienteCAMA,
                actividadMedicaPacienteINGRESO,
                actividadMedicaPacientePERFILINFECCIOSO,
                actividadMedicaPacientePERFILTOXEMICO,
                actividadMedicaPacienteIMAGENES,
                actividadMedicaPacientePARACLINICO
                )
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
EOD;

        return Conexion::insertFila($sql,[
            $actividadMedicaID,
            $personaID,
            $especialidadMedicaID,
            $actividadMedicaPacienteALERGIA,
            $actividadMedicaPacienteMEDICAMENTO,
            $actividadMedicaPacienteNOTA,
            $actividadMedicaPacienteDIAGNOSTICO,
            $servicioID,
            $actividadMedicaPacienteCAMA,
            $actividadMedicaPacienteINGRESO,
            $actividadMedicaPacientePERFILINFECCIOSO,
            $actividadMedicaPacientePERFILTOXEMICO,
            $actividadMedicaPacienteIMAGENES,
            $actividadMedicaPacientePARACLINICO
        ]);
    }

    public static function actualizarPaciente(
        $actividadMedicaPacienteID,
        $especialidadMedicaID,
        $actividadMedicaPacienteALERGIA,
        $actividadMedicaPacienteMEDICAMENTO,
        $actividadMedicaPacienteNOTA,
        $actividadMedicaPacienteDIAGNOSTICO,
        $servicioID,
        $actividadMedicaPacienteCAMA,
        $actividadMedicaPacienteINGRESO,
        $actividadMedicaPacientePERFILINFECCIOSO,
        $actividadMedicaPacientePERFILTOXEMICO,
        $actividadMedicaPacienteIMAGENES,
        $actividadMedicaPacientePARACLINICO
    ){
        $sql=<<<EOD
            UPDATE `intranet`.`actividadesMedicasPaciente` SET
                especialidadMedicaID = ? ,
                actividadMedicaPacienteALERGIA = ?,
                actividadMedicaPacienteMEDICAMENTO = ? ,
                actividadMedicaPacienteNOTA = ?,
                actividadMedicaPacienteDIAGNOSTICO = ?,
                servicioID = ?,
                actividadMedicaPacienteCAMA =?,
                actividadMedicaPacienteINGRESO=?,
                actividadMedicaPacientePERFILINFECCIOSO=?,
                actividadMedicaPacientePERFILTOXEMICO=?,
                actividadMedicaPacienteIMAGENES=?
                actividadMedicaPacientePARACLINICO=?
                WHERE actividadMedicaPacienteID = ?
EOD;
        return Conexion::actualizarFila($sql,[
            $especialidadMedicaID,
            $actividadMedicaPacienteALERGIA,
            $actividadMedicaPacienteMEDICAMENTO,
            $actividadMedicaPacienteNOTA,
            $actividadMedicaPacienteDIAGNOSTICO,
            $servicioID,
            $actividadMedicaPacienteCAMA,
            $actividadMedicaPacienteINGRESO,
            $actividadMedicaPacientePERFILINFECCIOSO,
            $actividadMedicaPacientePERFILTOXEMICO,
            $actividadMedicaPacienteIMAGENES,
            $actividadMedicaPacientePARACLINICO,
            $actividadMedicaPacienteID
        ]);
    }


    public static function eliminarPacientesQueNoEstanEnLista($pacientes, $actividadMedicaID){
        $listPcientes = implode(',', $pacientes);
        $sql=<<<EOD
            DELETE FROM `intranet`.`actividadesMedicasPaciente` 
                WHERE actividadMedicaColaboradorID = ? AND actividadMedicaPacienteID NOT IN ($listPcientes)
EOD;
        return Conexion::eliminarFila($sql,[
            $actividadMedicaID
        ]); 
    }
    
 }