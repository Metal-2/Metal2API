<?php

class ColaboradorControlador extends Controladores {

    function colaboradorPorIdentificacion() {
        $colaborador = Colaboradores::porIdentificacion($this->identificacion);
    
        if($colaborador){
            if($colaborador->usuarioESTADO == "DESACTIVO"){
                echo Respuestas::exito("El colaborador con identificación " . $this->identificacion . " se encuentra en estado desactivo. ", $colaborador);
                return true;
            }
            echo Respuestas::exito("Colaborador Activo", $colaborador);
        }else{
            echo Respuestas::error("No se encontro colaborador asociado a la identificación " . $this->identificacion);
        }
        
    }

    function colaboradoresPorGruposDeTrabajo(){
        $grupoTrabajo = Colaboradores::porGrupoDeTrabajo($this->grupoTrabajoID);
        echo Respuestas::exito("Colaboradores", $grupoTrabajo);
    }
}
