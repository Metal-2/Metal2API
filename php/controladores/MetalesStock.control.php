<?php

class MetalesStockControlador extends Controladores
{


    function dataMetalesStock()
    {
        echo Respuestas::exito(
            MetalesStock::all()
        );
    }

    function dataMetalesStockById()
    {
        $metalStock = MetalesStock::dataById($this->metalStockID);

        if ($metalStock) {
            echo Respuestas::exito(
                $metalStock
            );
        }else{
            echo Respuestas::error(
                "metal does not exist."
            );
        }

        
    }
}