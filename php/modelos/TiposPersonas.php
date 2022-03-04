<?php 
 class TiposPersonas{

    const NATURAL=1;
    const JURIDICA=2;
    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`tipospersonas`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT;
        return Conexion::selectVariasFilas($sql);
    }
 }