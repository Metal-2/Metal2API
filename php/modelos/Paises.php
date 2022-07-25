<?php 
 class Paises{

    const SELECT=<<<EOD
        SELECT * FROM `paises`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT." ORDER BY paisNOMBRE";
        return Conexion::selectVariasFilas($sql);
    }
 }