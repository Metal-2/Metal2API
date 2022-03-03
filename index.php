<?php
require_once 'headers.php';
require_once 'config.php';


if (!$_POST) {
    $_POST= (array) json_decode(file_get_contents("php://input"));
}
if (isset($_POST['controlador'])) {
    $controlador = ucfirst($_POST['controlador']);
    include 'php/controladores/' . $controlador . '.control.php';
    $nombreClase = $controlador . 'Controlador';
    if (class_exists($nombreClase)) {
        $classCtrl = new $nombreClase();
        if ($classCtrl instanceof $nombreClase) {
            $nombreFuncion = lcfirst($_POST['operacion']);
            echo $classCtrl->$nombreFuncion();
        }
    }
}