<?php

class Respuestas {
    
    const EXITO = 'EXITO';
    const ERROR = 'ERROR';
    const ALERTA = 'ALERTA';
    const INFO = 'INFO';
    
    static public function respuesta($respuesta, $mensaje, $datos = null){
        $arrayRespuesta = array(
            'RESPUESTA' => $respuesta,
            'MENSAJE' => $mensaje,
            'DATOS' => $datos
          );
          $jsonRespuesta = json_encode($arrayRespuesta);
          return $jsonRespuesta;
    }

    static public function exito($mensaje = null, $datos = null) {
        if (is_array($mensaje)) {
            $datos = $mensaje;
            $mensaje = "";
        }

        if (is_object($mensaje)) {
            $array = (array) $mensaje;
            $mensaje = "";
            $datos = $array;
        }

        return self::respuesta(self::EXITO, $mensaje, $datos);
    }


    static public function error($mensaje, $datos = null) {
        return self::respuesta(self::ERROR, $mensaje, $datos);
    }

    static public function alerta($mensaje, $datos = null) {
        return self::respuesta(self::ALERTA, $mensaje, $datos);
    }
    static public function info($mensaje, $datos = null) {
        return self::respuesta(self::INFO, $mensaje, $datos);
    }

}