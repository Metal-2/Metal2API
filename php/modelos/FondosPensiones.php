<?php 
 class FondosPensiones{

    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`fondospensiones`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT;
        return Conexion::selectVariasFilas($sql);
    }
 }