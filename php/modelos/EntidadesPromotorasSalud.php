<?php 
 class EntidadesPromotorasSalud{

    const SELECT=<<<EOD
        SELECT * FROM `intranet`.`entidadespromotorassalud`     
EOD;
    
    public  static function todos(){
        $sql = self::SELECT." ORDER BY epsTITULO";
        return Conexion::selectVariasFilas($sql);
    }
 }