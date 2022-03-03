<?php 
 class UnidadesFuncionales{

    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`unidadesfuncionales`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT;
        return Conexion::selectVariasFilas($sql);
    }
 }