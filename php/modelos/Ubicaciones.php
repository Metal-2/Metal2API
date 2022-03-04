<?php 
 class Ubicaciones{

    const SELECT_MUNICIPIOS=<<<EOD
        SELECT * FROM `intranet`.`municipios`     
EOD;
    const SELECT_DEPARTAMENTOS=<<<EOD
        SELECT * FROM `intranet`.`departamentos`     
EOD;
    const SELECT_PAISES=<<<EOD
    SELECT * FROM `intranet`.`paises`     
EOD;
    
    public  static function todosDepartamento($paisID=47){
        $sql = self::SELECT_DEPARTAMENTOS."  WHERE paisID=? ";
        return Conexion::selectVariasFilas($sql,[$paisID]);
    }
   
   public  static function todosMunicipio(){
        $sql = self::SELECT_MUNICIPIOS;
        return Conexion::selectVariasFilas($sql);
    }

    public  static function todosPaises(){
        $sql = self::SELECT_PAISES;
        return Conexion::selectVariasFilas($sql);
    }

    public static function municipioPorDepartamento($departamentoID){
        $sql = self::SELECT_MUNICIPIOS . " WHERE departamentoID =?";
        return Conexion::selectVariasFilas($sql,[$departamentoID]);
    }

    public static function departentoPorPais($paisID){
        $sql = self::SELECT_DEPARTAMENTOS . " WHERE paisID =?";
        return Conexion::selectVariasFilas($sql,[$paisID]);
    }
 }