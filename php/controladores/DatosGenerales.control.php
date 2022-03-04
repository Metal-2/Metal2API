<?php

class DatosGeneralesControlador extends Controladores {

    function datosEntradaForm(){
            $tipoPersonas=TiposPersonas::todos();
            $tiposIdentificaciones=TiposIdentificaciones::todos();
            $unidadesFuncionales=UnidadesFuncionales::todos();
            $nivelesEscolares=NivelesEscolares::todos();
            $aseguradorasriesgoslaborales=AseguradorasRiesgosLaborales::todos();
            $fondospensiones=FondosPensiones::todos();
            $entidadespromotorassalud=EntidadesPromotorasSalud::todos();
            $cargos=Cargos::todos();
            $departamentos=Ubicaciones::todosDepartamento();
            $paises=Ubicaciones::todosPaises();
            echo Respuestas::exito("Datos de entra.",["Paises"=>$paises,"Departamentos"=>$departamentos,"Cargos"=>$cargos,"EntidadesPromotorasSalud"=>$entidadespromotorassalud,"FondosPensiones"=>$fondospensiones,"AseguradorasRiesgosLaborales"=>$aseguradorasriesgoslaborales,"UnidadesFuncionales"=>$unidadesFuncionales,"TipoPersonas"=>$tipoPersonas,"TiposIdentificaciones"=>$tiposIdentificaciones,"NivelesEscolares"=>$nivelesEscolares]);
    }

    function grupoTrabajoPorUnidad(){
        $gruposTrabajo=GruposTrabajos::todosPorUnidadId($this->unidadFuncionalID);
        echo Respuestas::exito("Datos de entra.",["GruposTrabajo"=>$gruposTrabajo]);
    }
    
    function departentoPorPais(){
        $departamentos=Ubicaciones::departentoPorPais($this->paisID);
        echo Respuestas::exito("Datos de entra.",["Departamentos"=>$departamentos]);
    }
  
  
    function municipioPorDepartamento(){
        $municipios=Ubicaciones::municipioPorDepartamento($this->departamentoID);
        echo Respuestas::exito("Datos de entra.",["Municipios"=>$municipios]);
    }
  
   function datosEntradaDocumentos(){
      $carpetas=GestionDocumental::CarpetaTodos();
      echo Respuestas::exito("Datos de entra.",["Carpetas"=>$carpetas]);
   }


   function datosDashboard(){
    $nDocumentos=GestionDocumental::cantidadTotalDocumentos();
    echo Respuestas::exito("Datos de entra.",["NumeroDocumentos"=>$nDocumentos]);
   }

}