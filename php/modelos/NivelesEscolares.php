<?php 
 class NivelesEscolares{

    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`nivelesescolares`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT;
        return Conexion::selectVariasFilas($sql);
    }
 }