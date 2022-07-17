<?php

namespace App\Services\Base;
use GuzzleHttp\Client;
use App\Services\Base\UHIExceptionHandler as UHIExceptionHandler;

class BaseAPIService{

    public function sendHttpRequest(string $sMethod, string $sURL, array $aData = [], array $aHeaders = []){

        try {
            
            $aResponse = array();
            
            $aURL = parse_url($sURL);
            $sRequestURL = $aURL['scheme']."://".$aURL['host'].(isset($aURL['port']) ? ":".$aURL['port'] : '');
            $sRequestPath = $aURL['path'] ?? '';
            $aRequestData = [
                'headers' => $aHeaders,
                'json' => $aData
            ];

            // Guzzlehttp API client
            $oAPIRequestClient = $this->getClient($sRequestURL);

            $oResponse = $oAPIRequestClient->request($sMethod, $sRequestPath, $aRequestData);

            // response with status code
            $aResponse = (array) json_decode((string) $oResponse->getBody());
            $aResponse['status_code'] = $oResponse->getStatusCode();

        } catch (\GuzzleHttp\Exception\ConnectException $e){

            $aResponse = UHIExceptionHandler::handleClientConnectException($e);

        } catch (\GuzzleHttp\Exception\ServerException $e){

            $aResponse = UHIExceptionHandler::handleInternalServerException($e);

        } catch (\GuzzleHttp\Exception\ClientException $e){

            $aResponse = UHIExceptionHandler::handleClientException($e);

        }  catch (\Exception $e){

            $aResponse = UHIExceptionHandler::handleGeneralExceptions($e);

        } catch (\Throwable $th) {

            $aResponse = UHIExceptionHandler::handleGeneralExceptions($th);

        }

        return $aResponse;
    }

    public function getClient(string $sURL){
        return new Client([
            'base_uri' => $sURL,
            'timeout'  => 30,
        ]);
    }

    // function to get NDHM API token
    public function getNewGatewayAPIToken(){
        $oResponse = (new Client([
            'base_uri' => env('NDHM_GATEWAY_SERVICE_URL'),
            'timeout'  => 20,
        ]))->request('post', '/gateway/v0.5/sessions', [
            'json' => [
                "clientId" => env('NDHM_CLIENT_ID'),
                "clientSecret" => env('NDHM_CLIENT_SECRET')
            ]
        ]);

        $aData = (array) json_decode((string)$oResponse->getBody());

        return $aData['accessToken'];
    }
}