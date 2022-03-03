<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ActividadesMedicasControlador extends Controladores {

    function cargaDatosIniciales(){

        $tiposIdentificaciones = TiposIdentificaciones::todos();
        $servicios =  ModeloBasicoGeneral::serviciosTodos();
        $especialidadesMedicas = ModeloBasicoGeneral::especialidadesMedicasTodos();
        $eps = EntidadesPromotorasSalud::todos();
        $colaboradoresActividadesMedicas = ModeloBasicoGeneral::colaboradoresActividadesMedicasTodos();
        
        echo Respuestas::exito([
            "EspecialidadesMedicas"=>$especialidadesMedicas,
            "Servicios"=>$servicios,
            "Eps"=>$eps,
            "TiposIdentificaciones"=>$tiposIdentificaciones,
            "ColaboradoresActividadesMedicas" => $colaboradoresActividadesMedicas
        ]);
    }

    public function buscarPorUsuario(){
        $actividadMedica= ActividadesMedicas::buscarPorUsuarioID($this->usrLogin);
        echo Respuestas::exito($actividadMedica);
    }

    public function buscarPorActividadMedicaColaboradorASIGNACION(){
        $usuario=Usuarios::dato($this->usrLogin);
        $actividadMedica= ActividadesMedicas::buscarActividadMedicaColaboradorASIGNACION($usuario->colaboradorID);
        echo Respuestas::exito($actividadMedica);
    }

    public function buscarTodosPacientesPorActividadMedicaID(){
        $actividadMedicaColaborador = ActividadesMedicas::buscarPorActividadMedicaColaboradorID($this->actividadMedicaColaboradorID);
        $actividadesMedicasPacientes = ActividadesMedicas::buscarTodosPacientesPorActividadMedicaID($this->actividadMedicaColaboradorID);
        echo Respuestas::exito([
            "ActividadMedica"=>$actividadMedicaColaborador,
            "Pacientes"=>$actividadesMedicasPacientes,
        ]);
    }

    public function exportarTodosPacientesPorActividadMedicaID(){

        $actividadMedicaColaborador = ActividadesMedicas::buscarPorActividadMedicaColaboradorID($this->actividadMedicaColaboradorID);
        $actividadesMedicasPacientes = ActividadesMedicas::buscarTodosPacientesPorActividadMedicaID($this->actividadMedicaColaboradorID);
        $spreadsheet = new Spreadsheet();
        $arrayData = [
            [NULL],
            [NULL,NULL,NULL,"FECHA :", "ENTREGADA POR : ", "ASIGNADA A :", "SERVICIO : "],
            [NULL,NULL,NULL,  date("Y-m-d",strtotime($actividadMedicaColaborador->actividadMedicaColaboradorFCHCREO)), $actividadMedicaColaborador->personaRAZONSOCIALENTREGA, $actividadMedicaColaborador->personaRAZONSOCIAL,$actividadesMedicasPacientes[0]->servicioNOMBRE],
            [NULL],
            [NULL],
            [
                "IDENTIFICACIÓN",
                "PACIENTE",
                "EDAD",
                "FECHA DE INGRESO",
                "CAMA",
                "EPS",
                "ESPECIALIDAD",
                "DIAGNOSTICO",
                "ALERGIA",
                "MEDICAMENTOS",
                "PARACLINICOS",
                "PENDIENTE"
            ]
        ];

        foreach ($actividadesMedicasPacientes as $paciente) {
            array_push($arrayData,[
                $paciente->personaIDENTIFICACION,
                $paciente->personaRAZONSOCIAL,
                $paciente->personaEDAD,
                $paciente->actividadMedicaPacienteINGRESO,
                $paciente->actividadMedicaPacienteCAMA,
                $paciente->epsTITULO,
                $paciente->especialidadMedicaNOMBRE,
                $paciente->actividadMedicaPacienteDIAGNOSTICO,
                $paciente->actividadMedicaPacienteALERGIA,
                $paciente->actividadMedicaPacienteMEDICAMENTO,
                $paciente->actividadMedicaPacientePARACLINICO,
                $paciente->actividadMedicaPacienteNOTA,
            ]);
        }
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];
        
    
        $sheet = $spreadsheet->getActiveSheet()->fromArray(
            $arrayData,  
            NULL, 
            'A1' 
        )/* ->getStyle('A1:U8')->applyFromArray($styleArray) */;
        $urlFile = "INTRANET_ARCHIVOS/UTILIDADES/ACTIVIDADES MEDICAS/ACTIVIDAD MEDICA - ".uniqid().".xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save($urlFile);
        echo Respuestas::exito([
            "file"=>$urlFile
        ]);
    }

    public function guardar(){
        $actividadMedicaID=empty($this->actividadMedicaColaboradorID)?NULL:$this->actividadMedicaColaboradorID;
        $actividadMedicaColaboradorASIGNACION=empty($this->actividadMedicaColaboradorASIGNACION)?NULL:$this->actividadMedicaColaboradorASIGNACION;
        $pacientes=empty($this->pacientes)?NULL:$this->pacientes;
        $servicioGlobalID=empty($this->servicioGlobalID)?NULL:$this->servicioGlobalID;
        $arrayPacientesID=[];

        if (!$actividadMedicaID) {
            $actividadMedicaID= ActividadesMedicas::guardar(
                $this->usrLogin,
                $actividadMedicaColaboradorASIGNACION
            );
        }else{
            ActividadesMedicas::actualizar(
                $actividadMedicaID,
                $actividadMedicaColaboradorASIGNACION
            );
        }

        foreach ($pacientes as $paciente) {
            $personaID = Personas::gestionarPersona(
                TiposPersonas::NATURAL,
                $paciente->tipoIdentificacionID,
                $paciente->personaIDENTIFICACION,
                NULL,NULL,NULL,NULL,NULL,NULL,
                $paciente->personaRAZONSOCIAL,
                NULL, NULL, NULL, NULL,NULL,
                NULL,NULL,$paciente->epsID, NULL,
                NULL, NULL, NULL, NULL, NULL, NULL, $paciente->personaEDAD
            );
            
            $paciente = ActividadesMedicas::gestionarPaciente(
                $actividadMedicaID,
                $personaID,
                $paciente->especialidadMedicaID,
                $paciente->actividadMedicaPacienteALERGIA,
                $paciente->actividadMedicaPacienteMEDICAMENTO,
                $paciente->actividadMedicaPacienteNOTA,
                $paciente->actividadMedicaPacienteDIAGNOSTICO,
                $servicioGlobalID,
                $paciente->actividadMedicaPacienteCAMA,
                $paciente->actividadMedicaPacienteINGRESO,
                $paciente->actividadMedicaPacientePERFILINFECCIOSO,
                $paciente->actividadMedicaPacientePERFILTOXEMICO,
                $paciente->actividadMedicaPacienteIMAGENES,
                $paciente->actividadMedicaPacientePARACLINICO
            );

            if ($paciente) {
                array_push($arrayPacientesID, $paciente);
            }
        }

        if (count($arrayPacientesID) == count($pacientes)) {
            ActividadesMedicas::eliminarPacientesQueNoEstanEnLista($arrayPacientesID, $actividadMedicaID);
            echo Respuestas::exito("Actividad Medica Exitosa.");
        }else{
            echo Respuestas::error("Error al guardar la actividad medica.");
        }
        
    }

}
