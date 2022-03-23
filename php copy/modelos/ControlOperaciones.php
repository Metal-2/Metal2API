<?php 
 class ControlOperaciones{

    const SELECT_COMPLETO=<<<EOD
        SELECT 
            operacionesusuarios.*,
            controloperaciones.*
        FROM `operacionesusuarios` 
        LEFT JOIN `controloperaciones` 
            ON operacionesusuarios.controlOperacionID = controloperaciones.controlOperacionID
EOD;

    const SELECT_OPERACIONES=<<<EOD
        SELECT 
            controloperaciones.*
        FROM `controloperaciones`
EOD;
    const SELECT_COMPONENTES=<<<EOD
        SELECT 
            controlcomponentes.*
        FROM `controlcomponentes` 
EOD;

    public static function menuDelUsuario($usuarioID){
         $sql=self::SELECT_COMPLETO ." WHERE operacionesusuarios.usuarioID = ? AND controlOperacionMENU = 'SI' ORDER BY controlOperacionORDEN ASC" ;
        return Conexion::selectVariasFilas($sql,[$usuarioID]);
    }

    public static function todosMenu(){
        $sql=self::SELECT_OPERACIONES ." WHERE controlOperacionMENU = 'SI'  ORDER BY controlOperacionORDEN ASC" ;
        return Conexion::selectVariasFilas($sql);
    }

    public static function todosComponentes(){
        $sql=self::SELECT_COMPONENTES . " ORDER BY controlComponenteORDEN ASC";
        return Conexion::selectVariasFilas($sql);
    }

    public static function todosOperaciones(){
        $sql=self::SELECT_OPERACIONES ;
        return Conexion::selectVariasFilas($sql);
    }

    public static function todosOperacionesDelUusario($usuarioID){
        $sql=self::SELECT_COMPLETO ." WHERE operacionesusuarios.usuarioID = ? " ;
        return Conexion::selectVariasFilas($sql,[$usuarioID]);
    }

    public static function buscarOperacionesDelUusario($usuarioID,$controlOperacionID){
        $sql=self::SELECT_COMPLETO ." WHERE operacionesusuarios.usuarioID = ? AND  operacionesusuarios.controlOperacionID = ? " ;
        return Conexion::selectUnaFila($sql,[$usuarioID,$controlOperacionID]);
    }

    public static function guardarOperacionUsuario($usuarioID,$controlOperacionID){
        $sql=<<<EOD
            INSERT INTO  `intranet`.`operacionesusuarios` (controlOperacionID, usuarioID)
            VALUES (?,?)
EOD;
       return Conexion::insertFila($sql,[$controlOperacionID,$usuarioID]);
    }

    public static function eliminarOperacionUsuario($usuarioID,$controlOperacionID){
        $sql=" DELETE FROM intranet.operacionesusuarios WHERE operacionesusuarios.usuarioID = ? AND  operacionesusuarios.controlOperacionID = ?";
        return Conexion::eliminarFila($sql,[$usuarioID,$controlOperacionID]);
    }
    
 }