<?php 
 class ModeloBasicoGeneral{
    
    public static function diagnosticosTodos(){
        $sql="SELECT * FROM `intranet`.`diagnosticos` ";
        return Conexion::selectVariasFilas($sql);
    }

    public static function serviciosTodos(){
        $sql="SELECT * FROM `intranet`.`servicios` ";
        return Conexion::selectVariasFilas($sql);
    }

    public static function especialidadesMedicasTodos(){
        $sql="SELECT * FROM `intranet`.`especialidadesMedicas` ";
        return Conexion::selectVariasFilas($sql);
    }

    public static function colaboradoresActividadesMedicasTodos(){
        $sql = <<<EOD
        SELECT
            personas.*,
            colaboradores.*
        FROM
            `intranet`.`colaboradoresActividadesMedicas`
            
        LEFT JOIN `intranet`.`colaboradores` 
            ON colaboradoresActividadesMedicas.colaboradorID = colaboradores.colaboradorID
        LEFT JOIN `intranet`.`personas` 
            ON colaboradores.personaID = personas.personaID
            ORDER BY personas.personaRAZONSOCIAL ASC
    EOD;

        return Conexion::selectVariasFilas($sql);
    }

 }