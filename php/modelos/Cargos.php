<?php 
 class Cargos{

    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`cargos`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT . " ORDER BY cargoTITULO ASC ";
        return Conexion::selectVariasFilas($sql);
    }
 }