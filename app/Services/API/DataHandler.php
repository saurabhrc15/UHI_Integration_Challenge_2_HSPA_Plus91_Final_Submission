<?php

namespace App\Services\API;

class DataHandler{

    // Function to set request data
    public static function objectToArray($oDataObject){
        $aDataArray = json_decode(json_encode($oDataObject), true);
        return $aDataArray;
    }
}