<?php

namespace App\Repositories\Client;

use App\Models\Client\ClientModel as ClientModel;
use App\Services\API\DataHandler as DataHandler;

class ClientRepo{

    public function fCreateClient(string $sClientName, string $sClientURL){

        $aClientDetails = array(
            'client_name' => $sClientName,
            'client_url' => $sClientURL,
            'client_key' => bin2hex(openssl_random_pseudo_bytes(10)),
            'client_secret' => bin2hex(openssl_random_pseudo_bytes(16)),
            'added_on' => date("Y-m-d H:i:s"),
            'deleted' => 0
        );

        // Add client details
        $iClientID = (new ClientModel)->fAddNewClient($aClientDetails);

        // Return added client details
        if($iClientID){
            $oClientDetails = (new ClientModel)->fGetClientDetailsByClientID($iClientID);
            return $oClientDetails;
        }
    }

    public function fDeleteClient(string $sClientKey){
        $bResult = (new ClientModel)->fDeleteClient($sClientKey);
        return $bResult;
    }

    public function fGetClientByClientKey(string $sClientKey){
        return (new ClientModel)->fGetClientDetailsByClientKey($sClientKey);
    }

    public function fGetClientByClientID(int $iClientID){
        $oClientDetails = (new ClientModel)->fGetClientDetailsByClientID($iClientID);
        return DataHandler::objectToArray($oClientDetails);
    }
}