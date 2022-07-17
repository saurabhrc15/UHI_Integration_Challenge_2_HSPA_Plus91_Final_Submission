<?php

namespace App\Services\Discovery;
use App\Repositories\Discovery\DiscoveryRepo as DiscoveryRepo;
use App\Repositories\Client\ClientFacilityRepo as ClientFacilityRepo;
use App\Services\API\APIService as APIService;
use App\Services\API\DataHandler as DataHandler;

class DiscoveryRequest{

    public function searchRequest(array $aRequestData){

        $sMessageID = isset($aRequestData['context']['message_id']) ? $aRequestData['context']['message_id'] : '';
        $sTransactionID = isset($aRequestData['context']['transaction_id']) ? $aRequestData['context']['transaction_id'] : '';

        $aResponse = array(
            "error" => [
            ],
            "message" => [
                "ack" => [
                    "status" => "ACK"
                ]
            ]
        );

        // Store discovery request
        $iStoreDiscoveryRequest = (new DiscoveryRepo)->addSearchRequest($sMessageID, $sTransactionID, $aRequestData);

        // On Search Request
        $this->onSearchRequest($aRequestData);

        return $aResponse;

    }

    // Function to add callback request for add care context request
    public function onSearchRequest(array $aRequestData){
        //$aRequestData = (new DiscoveryRepo)->getPendingSearchRequests();

        $aMedixcelFacilities = (new ClientFacilityRepo)->getAllClientFacilities();
        $sMessageID = isset($aRequestData['context']['message_id']) ? $aRequestData['context']['message_id'] : '';
        $sConsumerURL = isset($aRequestData['context']['consumer_uri']) ? $aRequestData['context']['consumer_uri'] : '';
        $sTransactionID = isset($aRequestData['context']['transaction_id']) ? $aRequestData['context']['transaction_id'] : '';

        // Send request to medixcel
        foreach ($aMedixcelFacilities as $iIndex => $aFacility) {

            $iClientID = $aFacility['client_id'];
            $iClinicID = $aFacility['clinic_id'];
            $aRequestData['iClinicID'] = $iClinicID;

            // Set client ID
            config(['medixcel.client.client_id' => $iClientID]);
            config(['medixcel.client.clinic_id' => $iClinicID]);

            $sClientEndpoint = "/api/hspa/search";
            $aMedixcelSearchResponse = (new APIService)->sendRequestToClient("POST", $sClientEndpoint, $aRequestData);
            $aOnSearchRequest = DataHandler::objectToArray($aMedixcelSearchResponse);
            $sConsumerURL = $sConsumerURL."/on_search";

            $aOnSearchRequest['context']['provider_id'] = $aFacility['provider_id'];
            $aOnSearchRequest['context']['provider_uri'] = env('PROVIDER_CALLBACK_URL');

            // Sent on_search callback request
            $aOnSearchResponse = (new APIService)->sendRequest("POST", $sConsumerURL, $aOnSearchRequest);

            // Add search response details
            $iStoreSearchResponse = (new DiscoveryRepo)->addSearchResponseDetails($sMessageID, $sTransactionID, $iClientID, $iClinicID, $aRequestData, $aMedixcelSearchResponse, $aMedixcelSearchResponse);
        }

        // Update search request callback status
        $sCallbackStatus = "completed";
        $bCallbackUpdateResult = (new DiscoveryRepo)->updateSeachRequestCallbackStatus($sMessageID, $sCallbackStatus);

        return ['message_id' => $sMessageID, 'status' => "sent"];
    }
}