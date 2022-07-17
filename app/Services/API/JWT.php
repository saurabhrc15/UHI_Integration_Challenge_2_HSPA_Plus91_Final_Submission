<?php

namespace App\Services\API;

use \Firebase\JWT\JWT as JWTService;

class JWT{
    public static function encode(array $aData, string $sKey = null){
        if(!$sKey){
            $sKey = env('SERVICE_SECRET_KEY');
        }

        return JWTService::encode($aData, $sKey);
    }

    public static function decode(string $sJWT, string $sKey = null){
        if(!$sKey){
            $sKey = env('SERVICE_SECRET_KEY');
        }

        return (array) JWTService::decode($sJWT, $sKey, array('HS256'));
    }
}