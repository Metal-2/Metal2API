<?php

class Conexion extends PDO {

    public static $instancia = null;
    public function __construct() {
        try { 
            parent::__construct(
                'mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER, DB_PASSWORD, 
                    array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                        PDO::ATTR_PERSISTENT => true
                    )
            );
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'ERROR:' . $e->getMessage();
        }
    }

    public static function singleton() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public static function selectUnaFila($sqlQuery, $datosQuery = NULL) {
        $conn = Conexion::singleton();
        $rsl = $conn->prepare($sqlQuery);
        $rsl->execute($datosQuery);
        $datos = $rsl->fetch(PDO::FETCH_OBJ);
        if (!empty($datos)) {
            return $datos;
        }
        return NULL;
    }

    public static function selectVariasFilas($sqlQuery, $datosQuery = NULL) {
        $conn = Conexion::singleton();
        $rsl = $conn->prepare($sqlQuery);
        $rsl->execute($datosQuery);
        $datos = $rsl->fetchAll(PDO::FETCH_OBJ);
        if (!is_null($datos)) {
            return $datos;
        }
        return NULL;
    }

    public static function insertFila($sqlQuery, $datosQuery = NULL) {
        $conn = Conexion::singleton();
        $rsl = $conn->prepare($sqlQuery);
        $rsl->execute($datosQuery);
        $ultimoInsert = $conn->lastInsertId();
        if (!is_null($ultimoInsert)) {
            return $ultimoInsert;
        }
        return NULL;
    }

    public static function actualizarFila($sqlQuery, $datosQuery = NULL) {
        $conn = Conexion::singleton();
        $rsl = $conn->prepare($sqlQuery);
        $rsl->execute($datosQuery);
        $modificados = $rsl->rowCount();
        if (!is_null($modificados)) {
            return $modificados;
        }
        return NULL;
    }

    public static function eliminarFila($sqlQuery, $datosQuery = NULL) {
        $conn = Conexion::singleton();
        $rsl = $conn->prepare($sqlQuery);
        $rsl->execute($datosQuery);
        $eliminados = $rsl->rowCount();
        if (!is_null($eliminados)) {
            return $eliminados;
        }
        return NULL;
    }
}

Conexion::singleton();