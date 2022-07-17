<?php

namespace App\Repositories\Discovery;

use App\Models\Discovery\DiscoveryModel as DiscoveryModel;
use App\Services\API\DataHandler as DataHandler;

class DiscoveryRepo{

    // Add discovery request
    public function addSearchRequest(string $sMessageID, string $sTransactionID, array $aDiscoveryRequest){

        // check if search request is already added by message ID
        $oSearchRequest = (new DiscoveryModel)->fGetSearchRequestByMessageID($sMessageID);

        if(isset($oSearchRequest->message_id)){
            return 1;
        }

        // Add care context request
        $aDiscoveryRequestData = array(
            'message_id' => $sMessageID,
            'transaction_id' => $sTransactionID,
            'search_request' => json_encode($aDiscoveryRequest),
            'added_on' => date("Y-m-d H:i:s")
        );

        $iStoreResult = (new DiscoveryModel)->fAddSearchRequest($aDiscoveryRequestData);

        return $iStoreResult;
    }

    // Function to get all pending search requests
    public function getPendingSearchRequests(){
        $oSearchRequest = (new DiscoveryModel)->fGetPendingSearchRequests();
        return DataHandler::objectToArray($oSearchRequest);
    }

    // Function to get client details by message id & client id
    public function getClientByMessageAndClinicID($sMessageID, $iClinicID){
        $oClientDetails = (new DiscoveryModel)->fGetClientByMessageAndClinicID($sMessageID, $iClinicID);
        return DataHandler::objectToArray($oClientDetails);
    }

    // Function to get client details by transaction id & client id
    public function getClientByTransactionAndClinicID($sTransactionID, $iClinicID){
        $oClientDetails = (new DiscoveryModel)->fGetClientByTransactionAndClinicID($sTransactionID, $iClinicID);
        return DataHandler::objectToArray($oClientDetails);
    }

    // Function to get discovery request by transaction_id
    public function getDiscoveryRequestByTransactionID($sTransactionID){
        $oClientDetails = (new DiscoveryModel)->fGetDiscoveryRequestByTransactionID($sTransactionID);
        return DataHandler::objectToArray($oClientDetails);
    }

    // Add discovery response
    public function addSearchResponseDetails(string $sMessageID, string $sTransactionID, int $iClientID, int $iClinicID, array $aSearchRequestToMedixcel, array $aSearchResponseFromMedixcel, array $aOnSearchRequest){

        // Add care context request
        $aSearchReponseRequest = array(
            'message_id' => $sMessageID,
            'transaction_id' => $sTransactionID,
            'client_id' => $iClientID,
            'clinic_id' => $iClinicID,
            'search_request_to_medixcel' => json_encode($aSearchRequestToMedixcel),
            'search_response_from_medixcel' => json_encode($aSearchResponseFromMedixcel),
            'on_search_request' => json_encode($aOnSearchRequest),
            'added_on' => date("Y-m-d H:i:s")
        );

        $iStoreResult = (new DiscoveryModel)->fAddSearchResponseDetails($aSearchReponseRequest);

        return $iStoreResult;
    }

    // Add discovery response
    public function updateSeachRequestCallbackStatus(string $sMessageID, string $sCallbackStatus){
        $bUpdateResult = (new DiscoveryModel)->fUpdateSeachRequestCallbackStatus($sMessageID, $sCallbackStatus);
        return $bUpdateResult;
    }
}