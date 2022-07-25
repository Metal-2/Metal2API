<?php

require 'vendor/autoload.php';

session_start();

error_reporting(E_ALL);

ini_set('display_errors', TRUE);

ini_set('display_startup_errors', TRUE);

date_default_timezone_set('America/Bogota');


define('DS', DIRECTORY_SEPARATOR);

define('NOMBRE_APP', "CyptoMetal");
define('DB_HOST', "localhost");
define('DB_NAME', "artprime_metal2");
define('DB_USER', "root");
define('DB_PASSWORD', "");

define('USER_EMAIL', "jhoropertuz2@gmail.com");
define('PASSWORD_EMAIL', "opkpigiacmonljkf");
define('USER_NAME_EMAIL', "CryptoMetal");
define('PATH_TEMPLATE_EMAIL', "php/utilidades/templateEmail/");
define('URL_APP', "http://localhost:4200/#/");


spl_autoload_register('autoCargaModelos');



function autoCargaModelos($className) {

    $path = 'php/modelos/';
    $file = $path . $className . '.php';

    if (file_exists($file) == false) {
        include "php/clases/" . $className . '.clase.php';
    }else{
        include ($file);
    }
}

if (!defined('DIR_BASE')) {

    define('DIR_BASE', __DIR__ . DIRECTORY_SEPARATOR);

}

 

