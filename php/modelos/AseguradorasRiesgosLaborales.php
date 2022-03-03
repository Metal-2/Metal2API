<?php 
 class AseguradorasRiesgosLaborales{

    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`aseguradorasriesgoslaborales`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT;
        return Conexion::selectVariasFilas($sql);
    }
 }