<?php

/* require_once 'vendor/autoload.php';
require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';
require_once 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php'; */
require_once 'php/clases/Controladores.clase.php';
require_once 'php/clases/Respuestas.clase.php';
require_once 'php/utilidades/Caracteres.php';
require 'vendor/autoload.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Bogota');

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
define('DS', DIRECTORY_SEPARATOR);
define('NOMBRE_APP', "Solicitud Peticion Verbal");

spl_autoload_register('autoCargaModelos');

function autoCargaModelos($className) {
    $path = 'php/modelos/';
    include $path . $className . '.php';
}


if (!defined('DIR_BASE')) {
    define('DIR_BASE', __DIR__ . DIRECTORY_SEPARATOR);
}
