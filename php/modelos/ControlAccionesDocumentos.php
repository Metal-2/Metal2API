<?php 
 class ControlAccionesDocumentos{

    const VER="VER";
    const DESCARGA="DESCARGA";

    public static function registrarAccion($usuarioID,$documentoID,$accion){
         $sql=<<<EOD
         INSERT INTO  `intranet`.`controlaccionesdocumentos` (documentoID, usuarioID, controlAccionDocumentoTIPO)
         VALUES (?,?,?)
EOD;
        return Conexion::insertFila($sql,[$documentoID,$usuarioID,$accion]);
    }
 }