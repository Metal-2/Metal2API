<?php 
 class TiposIdentificaciones{

    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`tiposidentificaciones`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT;
        return Conexion::selectVariasFilas($sql);
    }
 }