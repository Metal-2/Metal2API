<?php 
 class GruposTrabajos{

    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`grupostrabajos`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT;
        return Conexion::selectVariasFilas($sql);
    }

    public static function todosPorUnidadId($unidadFuncionalID){
        $sql = self::SELECT . "WHERE unidadFuncionalID=?";
        return Conexion::selectVariasFilas($sql,[$unidadFuncionalID]);
    }
 }