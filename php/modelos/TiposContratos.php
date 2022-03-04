<?php 
 class TiposContratos{

    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`tiposcontratos`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT;
        return Conexion::selectVariasFilas($sql);
    }
 }