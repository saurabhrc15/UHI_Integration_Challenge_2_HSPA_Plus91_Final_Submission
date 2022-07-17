<?php

namespace App\Http\Middleware;

use Closure;
//use App\Repositories\ClientFacilityRepo;
use App\Services\JWT;
//use App\Services\Logger\GatewayRequestLogger as GatewayRequestLogger;

class GatewayToHSPARequests
{

    public function handle($request, Closure $next)
    {

        // Set configs
        /*config(['medixcel.client.hip_id' => $sHIPID]);
        config(['medixcel.client.client_key' => $aClientDetails['client_key']]);
        config(['medixcel.client.client_secret' => $aClientDetails['client_secret']]);
        config(['medixcel.client.clinic_id' => $aClientDetails['clinic_id']]);
        config(['medixcel.client.client_id' => $aClientDetails['client_id']]);

        // Request data
        $sRequestPath = $request->getPathInfo();
        $sRequestMethod = $request->getMethod();
        $aRequestHeaders = $request->header();
        $aRequestData = $request->getContent();*/

        // Reponse
        $aResponse = $next($request);

        // Log request
        //GatewayRequestLogger::logIncomingRequest($sRequestMethod, $sRequestPath, $aRequestData, $aResponse);

        return $aResponse;
    }
}