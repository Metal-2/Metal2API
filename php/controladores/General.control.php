<?php

class GeneralControlador extends Controladores
{


    function dataContrys()
    {
        echo Respuestas::exito(
            Paises::todos()
        );
    }
}
