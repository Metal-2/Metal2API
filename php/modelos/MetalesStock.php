<?php 
 class MetalesStock{

    const SELECT=<<<EOD
        SELECT * FROM `metalesstock`     
EOD;
    
    public  static function all(){
        $sql = self::SELECT;
        return Conexion::selectVariasFilas($sql);
    }

    public  static function dataById($metalStockID){
        $sql = self::SELECT . " WHERE metalStockID = ?";
        return Conexion::selectUnaFila($sql, [$metalStockID]);
    }
    
 }