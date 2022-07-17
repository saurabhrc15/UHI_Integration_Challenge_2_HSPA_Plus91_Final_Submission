<?php

namespace App\Services\API;

use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;
use App\Repositories\Client\ClientRepo as ClientRepo;
use App\Services\API\JWT;
use App\Services\Base\BaseAPIService as BaseAPIService;
use App\Models\Logger\Requestlogger as Requestlogger;

class APIService extends BaseAPIService{

    public function generateRequestID(){
        return Uuid::uuid4();
    }

    public function generateCurrentTimestamp(){
        return gmdate('Y-m-d\TH:i:s.u');
    }

    public function sendRequest($sMethod, $sURL, $aRequestData = [], $bAsync = false){

        // Required headers
        $aRequestHeaders = [
            //"Authorization" => "Bearer ".$this->getNewGatewayAPIToken()
        ];

        $aResponse = $this->sendHttpRequest($sMethod, $sURL, $aRequestData, $aRequestHeaders);

        // Log request
        $this->logRequest($sMethod, $sURL, $aRequestData, $aResponse);

        return $aResponse;
    }

    public function sendRequestToUHIGateway($sMethod, $sURL, $aRequestData = [], $bAsync = false){
        
        // Gateway URL
        $sURL = env('UHI_GATEWAY_SERVICE_URL').$sEndpoint;

        // Required headers
        $aRequestHeaders = [
            "Authorization" => "Bearer ".$this->getNewGatewayAPIToken()
        ];

        $aResponse = $this->sendHttpRequest($sMethod, $sURL, $aRequestData, $aRequestHeaders);

        // Log request
        $this->logRequest($sMethod, $sURL, $aRequestData, $aResponse);

        return $aResponse;
    }

    public function sendRequestToClient($sMethod, $sEndpoint, $aRequestData = []){

        $iClientID = config('medixcel.client.client_id');
        $aClient = (new ClientRepo)->fGetClientByClientID($iClientID);
        $sURL = $aClient['client_url'].$sEndpoint;

        $aRequestHeaders = [
            "Authorization" => "Bearer ".$this->getClientAPIToken($aClient),
        ];

        $aResponse = $this->sendHttpRequest($sMethod, $sURL, $aRequestData, $aRequestHeaders);

        // Log request
        $this->logRequest($sMethod, $sEndpoint, $aRequestData, $aResponse);

        return $aResponse;
    }

    // Fucntion to log request
    private function logRequest($sMethod, $sEndpoint, $aRequestData, $aResponse){

        $sRequestID = $aRequestData['requestId'] ?? null;
        $sTransactionID = $aRequestData['transactionId'] ?? null;
        $sHttpStatusCode = $aResponse['status_code'] ?? null;

        // Request log details
        $aRequestLog = array(
            'client_id' => config('medixcel.client.client_id'),
            'clinic_id' => config('medixcel.client.clinic_id'),
            'url' => $sEndpoint,
            'method' => $sMethod,
            'direction' => 'out',
            'request_id' => $sRequestID,
            'transaction_id' => $sTransactionID,
            'request' => json_encode($aRequestData),
            'response' => json_encode($aResponse),
            'response_code' => $sHttpStatusCode,
            'added_on' => date("Y-m-d H:i:s")
        );

        // Log request
        $bStoreResult = (new Requestlogger)->fAddRequestLog($aRequestLog);

        return $bStoreResult;
    }

    public static function getClientAPIToken($aClient){

        $sUHIAPIKey = env('UHI_SERVICE_API_KEY');

        return JWT::encode([
            "iat" => time(),
            "jti" => Uuid::uuid4(),
            "client_key" => $aClient['client_key'],
            'exp' => time() + 120
        ], $sUHIAPIKey, "HS256");
    }
}
