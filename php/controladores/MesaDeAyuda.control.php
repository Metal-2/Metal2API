<?php

class MesaDeAyudaControlador extends Controladores {

    function solicitudesPorUsuario(){
        $usuarioID = empty($this->usrLogin)?NULL:$this->usrLogin;
        $solicitudes = MesaDeAyuda::buscarPorUsuarioID($usuarioID);
        echo Respuestas::exito($solicitudes);
    }

    function solicitudesEnCurso(){
        $solicitudes = MesaDeAyuda::buscarPorEstado(MesaDeAyuda::ENCUERSO);
        echo Respuestas::exito($solicitudes);
    }

    function solicitudesPendientes(){
        $solicitudes = MesaDeAyuda::buscarPorEstado(MesaDeAyuda::PENDIENTE);
        echo Respuestas::exito($solicitudes);
    }

    function solicitudes(){
        $solicitudes = MesaDeAyuda::todo();
        echo Respuestas::exito($solicitudes);
    }

    function solicitudesPorID(){
        $mesaDeAyudaID = empty($this->mesaDeAyudaID)?NULL:$this->mesaDeAyudaID;
        $solicitudes = MesaDeAyuda::buscarPorId($mesaDeAyudaID);
        echo Respuestas::exito($solicitudes);
    }

    function actualzarGestion(){
        $mesaDeAyudaTIPOSOLICITUD = empty($this->mesaDeAyudaTIPOSOLICITUD)?NULL:$this->mesaDeAyudaTIPOSOLICITUD;
        $mesaDeAyudaUBICACIONDELSUCESO = empty($this->mesaDeAyudaUBICACIONDELSUCESO)?NULL:$this->mesaDeAyudaUBICACIONDELSUCESO;
        $mesaDeAyudaNUMEROINVENTARIOEQUIPO = empty($this->mesaDeAyudaNUMEROINVENTARIOEQUIPO)?NULL:$this->mesaDeAyudaNUMEROINVENTARIOEQUIPO;
        $mesaDeAyudaDESCRIPCION = empty($this->mesaDeAyudaDESCRIPCION)?NULL:$this->mesaDeAyudaDESCRIPCION;
        $mesaDeAyudaID = empty($this->mesaDeAyudaID)?NULL:$this->mesaDeAyudaID;
        $mesaDeAyudaNIVELPRIORIDAD = empty($this->mesaDeAyudaNIVELPRIORIDAD)?NULL:$this->mesaDeAyudaNIVELPRIORIDAD;
        $mesaDeAyudaUSUARIOMODIFICO = empty($this->usrLogin)?NULL:$this->usrLogin;
        $mesaDeAyudaCOLABORADORASIGNADO= empty($this->mesaDeAyudaCOLABORADORASIGNADO)?NULL:$this->mesaDeAyudaCOLABORADORASIGNADO;
        $mesaDeAyudaESTADO= empty($this->mesaDeAyudaESTADO)?NULL:$this->mesaDeAyudaESTADO;
        $mesaDeAyudaNOTA= empty($this->mesaDeAyudaNOTA)?NULL:$this->mesaDeAyudaNOTA;

        $actualizar = MesaDeAyuda::actualizarGestion(
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
        );
        if ($actualizar) {
            echo Respuestas::exito("Solicitud Actualizada.");
        }else{
            echo Respuestas::error("Error al actualizar la solicitud.");
        }
    }

    function guardar() {

        $mesaDeAyudaTIPOSOLICITUD = empty($this->mesaDeAyudaTIPOSOLICITUD)?NULL:$this->mesaDeAyudaTIPOSOLICITUD;
        $mesaDeAyudaUBICACIONDELSUCESO = empty($this->mesaDeAyudaUBICACIONDELSUCESO)?NULL:$this->mesaDeAyudaUBICACIONDELSUCESO;
        $mesaDeAyudaNUMEROINVENTARIOEQUIPO = empty($this->mesaDeAyudaNUMEROINVENTARIOEQUIPO)?NULL:$this->mesaDeAyudaNUMEROINVENTARIOEQUIPO;
        $mesaDeAyudaDESCRIPCION = empty($this->mesaDeAyudaDESCRIPCION)?NULL:$this->mesaDeAyudaDESCRIPCION;
        $mesaDeAyudaID = empty($this->mesaDeAyudaID)?NULL:$this->mesaDeAyudaID;
        $mesaDeAyudaNIVELPRIORIDAD = empty($this->mesaDeAyudaNIVELPRIORIDAD)?NULL:$this->mesaDeAyudaNIVELPRIORIDAD;
        $usuarioID = empty($this->usrLogin)?NULL:$this->usrLogin;
        
        if($mesaDeAyudaID){
            $actualizar = MesaDeAyuda::actualizar(
                $mesaDeAyudaID,
                $mesaDeAyudaNIVELPRIORIDAD,
                $mesaDeAyudaDESCRIPCION,
                $mesaDeAyudaNUMEROINVENTARIOEQUIPO,
                $mesaDeAyudaUBICACIONDELSUCESO,
                $mesaDeAyudaTIPOSOLICITUD
            );
            if ($actualizar) {
                echo Respuestas::exito("Solicitud Actualizada.");
            }else{
                echo Respuestas::error("Error al actualizar la solicitud.");
            }
        }else{

            $guardar = MesaDeAyuda::guardar(
                $usuarioID,
                $mesaDeAyudaNIVELPRIORIDAD,
                $mesaDeAyudaDESCRIPCION,
                $mesaDeAyudaNUMEROINVENTARIOEQUIPO,
                $mesaDeAyudaUBICACIONDELSUCESO,
                $mesaDeAyudaTIPOSOLICITUD 
            );
            if ($guardar) {
                echo Respuestas::exito("Solicitud Enviada.");
            }else{
                echo Respuestas::error("Error al enviar la solicitud.");
            }
           
        }
        
    }
}
