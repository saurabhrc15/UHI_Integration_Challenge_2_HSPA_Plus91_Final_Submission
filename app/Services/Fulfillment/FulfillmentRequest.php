<?php

namespace App\Services\Fulfillment;

use App\Repositories\Fulfillment\FulfillmentRepo as FulfillmentRepo;
use App\Repositories\Booking\BookingRepo as BookingRepo;
use App\Repositories\Client\ClientRepo as ClientRepo;
use App\Repositories\Client\ClientFacilityRepo as ClientFacilityRepo;
use App\Services\API\APIService as APIService;
use App\Services\API\DataHandler as DataHandler;

class FulfillmentRequest{

    public function statusRequest(array $aRequestData){

        $sMessageID = isset($aRequestData['context']['message_id']) ? $aRequestData['context']['message_id'] : '';
        $sTransactionID = isset($aRequestData['context']['transaction_id']) ? $aRequestData['context']['transaction_id'] : '';
        $sOrderID = isset($aRequestData['message']['order_id']) ? $aRequestData['message']['order_id'] : 0;

        // Booking client details
        $aBookingRequest = (new BookingRepo)->getBookingRequestByTransactionID($sTransactionID);
        $iClientID = $aBookingRequest['client_id'];
        $iClinicID = $aBookingRequest['clinic_id'];
        $aRequestData['iClinicID'] = $iClinicID;

        $aResponse = array(
            "error" => [
            ],
            "message" => [
                "ack" => [
                    "status" => "ACK"
                ]
            ]
        );

        // Store status request
        $iStoreStatusRequest = (new FulfillmentRepo)->addStatusRequest($sMessageID, $sTransactionID, $iClientID, $iClinicID, $aRequestData);

        // on_status request
        $this->onStatusRequest($aRequestData);

        return $aResponse;
    }

    // Function to add callback request for status request
    public function onStatusRequest(array $aRequestData){

        $sMessageID = isset($aRequestData['context']['message_id']) ? $aRequestData['context']['message_id'] : '';
        $sConsumerURL = isset($aRequestData['context']['consumer_uri']) ? $aRequestData['context']['consumer_uri'] : '';
        $aBookingRequest = (new BookingRepo)->getBookingRequestByMessageID($sMessageID);
        $iClientID = $aBookingRequest['client_id'];
        $iClinicID = $aBookingRequest['clinic_id'];
        $aRequestData['iClinicID'] = $iClinicID;

        // get client facility details
        $aClientFacitlity = (new ClientFacilityRepo)->getFacilityByClientAndClinicID($iClientID, $iClinicID);

        // Set client ID
        config(['medixcel.client.client_id' => $iClientID]);
        config(['medixcel.client.clinic_id' => $iClinicID]);

        $sClientEndpoint = "/api/hspa/status";
        $oStatusResponseFromClient = (new APIService)->sendRequestToClient("POST", $sClientEndpoint, $aRequestData);
        $oOnStatusRequest = DataHandler::objectToArray($oStatusResponseFromClient);
        $oOnStatusRequest['context']['provider_id'] = $aClientFacitlity['provider_id'];
        $oOnStatusRequest['context']['provider_uri'] = env('PROVIDER_CALLBACK_URL');
        $sConsumerURL = $sConsumerURL."/on_status";

        // Sent on_status callback request
        $aOnStatusResponse = (new APIService)->sendRequest("POST", $sConsumerURL, $oOnStatusRequest);

        // Store on select request
        $iStoreOnStatusRequest = (new FulfillmentRepo)->addOnStatusRequest($sMessageID, $oOnStatusRequest);

        return ['message_id' => $sMessageID, 'status' => "sent"];
    }
}