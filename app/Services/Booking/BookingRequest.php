<?php

namespace App\Services\Booking;
use App\Repositories\Booking\BookingRepo as BookingRepo;
use App\Repositories\Discovery\DiscoveryRepo as DiscoveryRepo;
use App\Repositories\Client\ClientRepo as ClientRepo;
use App\Repositories\Client\ClientFacilityRepo as ClientFacilityRepo;
use App\Services\API\APIService as APIService;
use App\Services\API\DataHandler as DataHandler;

class BookingRequest{

    public function selectRequest(array $aRequestData){

        $sMessageID = isset($aRequestData['context']['message_id']) ? $aRequestData['context']['message_id'] : '';
        $sTransactionID = isset($aRequestData['context']['transaction_id']) ? $aRequestData['context']['transaction_id'] : '';
        
        $aBookingRequest = (new DiscoveryRepo)->getDiscoveryRequestByTransactionID($sTransactionID);
        $iClientID = $aBookingRequest['client_id'];
        $iClinicID = $aBookingRequest['clinic_id'];
        $aRequestData['iClinicID'] = $iClinicID;

        // Get client dteails for order
        $aClientDetails = (new DiscoveryRepo)->getClientByTransactionAndClinicID($sTransactionID, $iClinicID);
        $iClientID = $aClientDetails['client_id'];

        $aResponse = array(
            "error" => [
            ],
            "message" => [
                "ack" => [
                    "status" => "ACK"
                ]
            ]
        );

        // Store select request
        $iStoreSelectRequest = (new BookingRepo)->addSelectRequest($sMessageID, $iClientID, $iClinicID, $sTransactionID, $aRequestData);

        // on_select request
        $this->onSelectRequest($aRequestData);

        return $aResponse;
    }

    // Function to add callback request for select request
    public function onSelectRequest(array $aRequestData){

        $sMessageID = isset($aRequestData['context']['message_id']) ? $aRequestData['context']['message_id'] : '';
        $sTransactionID = isset($aRequestData['context']['transaction_id']) ? $aRequestData['context']['transaction_id'] : '';
        $sConsumerURL = isset($aRequestData['context']['consumer_uri']) ? $aRequestData['context']['consumer_uri'] : '';
        $aBookingRequest = (new BookingRepo)->getBookingRequestByTransactionID($sTransactionID);
        $iClientID = $aBookingRequest['client_id'];
        $iClinicID = $aBookingRequest['clinic_id'];
        $aRequestData['iClinicID'] = $iClinicID;

        // Get client facility details
        $aClientFacitlity = (new ClientFacilityRepo)->getFacilityByClientAndClinicID($iClientID, $iClinicID);

        // Set client ID
        config(['medixcel.client.client_id' => $iClientID]);
        config(['medixcel.client.clinic_id' => $iClinicID]);

        $sClientEndpoint = "/api/hspa/select";
        $oSelectResponseFromClient = (new APIService)->sendRequestToClient("POST", $sClientEndpoint, $aRequestData);
        $aOnSelectRequest = DataHandler::objectToArray($oSelectResponseFromClient);
        $sConsumerURL = $sConsumerURL."/on_select";

        $aOnSelectRequest['context']['provider_id'] = $aClientFacitlity['provider_id'];
        $aOnSelectRequest['context']['provider_uri'] = env('PROVIDER_CALLBACK_URL');

        // Sent on_search callback request
        $aOnSelectResponse = (new APIService)->sendRequest("POST", $sConsumerURL, $aOnSelectRequest);

        // Store on select request
        $iStoreOnSelectRequest = (new BookingRepo)->addOnSelectRequest($sTransactionID, $aOnSelectRequest);

        return ['message_id' => $sMessageID, 'status' => "sent"];
    }

    public function initRequest(array $aRequestData){

        $sMessageID = isset($aRequestData['context']['message_id']) ? $aRequestData['context']['message_id'] : '';
        $sTransactionID = isset($aRequestData['context']['transaction_id']) ? $aRequestData['context']['transaction_id'] : '';
        
        // Get discovery request by transaction_id
        $aBookingRequest = (new DiscoveryRepo)->getDiscoveryRequestByTransactionID($sTransactionID);
        $iClientID = $aBookingRequest['client_id'];
        $iClinicID = $aBookingRequest['clinic_id'];
        $aRequestData['iClinicID'] = $iClinicID;

        // Get client dteails for order
        $aClientDetails = (new DiscoveryRepo)->getClientByTransactionAndClinicID($sTransactionID, $iClinicID);
        $iClientID = $aClientDetails['client_id'];

        $aResponse = array(
            "error" => [
            ],
            "message" => [
                "ack" => [
                    "status" => "ACK"
                ]
            ]
        );

        // Store init request
        $iStoreInitRequest = (new BookingRepo)->addInitRequest($sMessageID, $sTransactionID, $iClientID, $iClinicID, $aRequestData);

        // on_init request
        $this->onInitRequest($aRequestData);

        return $aResponse;
    }

    // Function to add callback request for init request
    public function onInitRequest(array $aRequestData){

        $sMessageID = isset($aRequestData['context']['message_id']) ? $aRequestData['context']['message_id'] : '';
        $sTransactionID = isset($aRequestData['context']['transaction_id']) ? $aRequestData['context']['transaction_id'] : '';
        $sConsumerURL = isset($aRequestData['context']['consumer_uri']) ? $aRequestData['context']['consumer_uri'] : '';
        
        // Get discovery request by transaction_id
        $aBookingRequest = (new DiscoveryRepo)->getDiscoveryRequestByTransactionID($sTransactionID);
        $iClientID = $aBookingRequest['client_id'];
        $iClinicID = $aBookingRequest['clinic_id'];
        $aRequestData['iClinicID'] = $iClinicID;

        // Get client facility details
        $aClientFacitlity = (new ClientFacilityRepo)->getFacilityByClientAndClinicID($iClientID, $iClinicID);

        // Set client ID
        config(['medixcel.client.client_id' => $iClientID]);
        config(['medixcel.client.clinic_id' => $iClinicID]);

        $sClientEndpoint = "/api/hspa/init";
        $oSelectResponseFromClient = (new APIService)->sendRequestToClient("POST", $sClientEndpoint, $aRequestData);
        $aOnInitRequest = DataHandler::objectToArray($oSelectResponseFromClient);
        $aOnInitRequest['context']['provider_id'] = $aClientFacitlity['provider_id'];
        $aOnInitRequest['context']['provider_uri'] = env('PROVIDER_CALLBACK_URL');
        $sConsumerURL = $sConsumerURL."/on_init";

        // Sent on_search callback request
        $aOnInitResponse = (new APIService)->sendRequest("POST", $sConsumerURL, $aOnInitRequest);

        // Store on select request
        $iStoreOnInitRequest = (new BookingRepo)->addOnInitRequest($sTransactionID, $aOnInitRequest);

        return ['message_id' => $sMessageID, 'status' => "sent"];
    }

    public function confirmRequest(array $aRequestData){

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

        // Store init request
        $iStoreInitRequest = (new BookingRepo)->addConfirmRequest($sTransactionID, $aRequestData);

        // on_confirm request
        $this->onConfirmRequest($aRequestData);

        return $aResponse;
    }

    // Function to add callback request for confirm request
    public function onConfirmRequest(array $aRequestData){

        $sMessageID = isset($aRequestData['context']['message_id']) ? $aRequestData['context']['message_id'] : '';
        $sTransactionID = isset($aRequestData['context']['transaction_id']) ? $aRequestData['context']['transaction_id'] : '';
        $sConsumerURL = isset($aRequestData['context']['consumer_uri']) ? $aRequestData['context']['consumer_uri'] : '';
        $aBookingRequest = (new BookingRepo)->getBookingRequestByTransactionID($sTransactionID);
        $iClientID = $aBookingRequest['client_id'];
        $iClinicID = $aBookingRequest['clinic_id'];
        $aRequestData['iClinicID'] = $iClinicID;

        // Get client facility details
        $aClientFacitlity = (new ClientFacilityRepo)->getFacilityByClientAndClinicID($iClientID, $iClinicID);

        // Set client ID
        config(['medixcel.client.client_id' => $iClientID]);
        config(['medixcel.client.clinic_id' => $iClinicID]);

        $sClientEndpoint = "/api/hspa/confirm";
        $oConfirmResponseFromClient = (new APIService)->sendRequestToClient("POST", $sClientEndpoint, $aRequestData);
        $aOnConfirmRequest = DataHandler::objectToArray($oConfirmResponseFromClient);
        $aOnConfirmRequest['context']['provider_id'] = $aClientFacitlity['provider_id'];
        $aOnConfirmRequest['context']['provider_uri'] = env('PROVIDER_CALLBACK_URL');
        $sConsumerURL = $sConsumerURL."/on_confirm";

        // Sent on_search callback request
        $aOnConfirmResponse = (new APIService)->sendRequest("POST", $sConsumerURL, $aOnConfirmRequest);

        // Store on select request
        $iStoreOnInitRequest = (new BookingRepo)->addOnConfirmRequest($sTransactionID, $aOnConfirmRequest);

        return ['message_id' => $sMessageID, 'status' => "sent"];
    }
}