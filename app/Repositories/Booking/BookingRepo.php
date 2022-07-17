<?php

namespace App\Repositories\Booking;

use App\Models\Booking\BookingModel as BookingModel;
use App\Services\API\DataHandler as DataHandler;

class BookingRepo{

    // Add select request
    public function addSelectRequest(string $sMessageID, int $iClientID, int $iClinicID, string $sTransactionID, array $aSelectRequest){

        // select request data
        $aSelectRequestData = array(
            'message_id' => $sMessageID,
            'client_id' => $iClientID,
            'clinic_id' => $iClinicID,
            'transaction_id' => $sTransactionID,
            'select_request' => json_encode($aSelectRequest),
            'added_on' => date("Y-m-d H:i:s")
        );

        $iStoreResult = (new BookingModel)->fAddSelectRequest($aSelectRequestData);

        return $iStoreResult;
    }

    // on_select request data
    public function addOnSelectRequest(string $sTransactionID, array $aOnSelectRequest){

        // on_select request data
        $aOnSelectRequestData = array(
            'transaction_id' => $sTransactionID,
            'on_select_request' => json_encode($aOnSelectRequest)
        );

        $iStoreResult = (new BookingModel)->fAddOnSelectRequest($aOnSelectRequestData);

        return $iStoreResult;
    }

    // Add init request
    public function addInitRequest(string $sMessageID, string $sTransactionID, int $iClientID, int $iClinicID, array $aInitRequest){

        // init request data
        $aInitRequestData = array(
            'message_id' => $sMessageID,
            'client_id' => $iClientID,
            'clinic_id' => $iClinicID,
            'transaction_id' => $sTransactionID,
            'init_request' => json_encode($aInitRequest),
            'added_on' => date("Y-m-d H:i:s")
        );

        $oBookingRequest = (new BookingModel)->fGetBookingRequestByTransactionID($sTransactionID);
        $aBookingRequest = DataHandler::objectToArray($oBookingRequest);

        if(!isset($aBookingRequest['transaction_id'])){
            $iStoreResult = (new BookingModel)->fAddInitRequest($aInitRequestData);
        }else{
            $iStoreResult = (new BookingModel)->fUpdateInitRequest($aInitRequestData);
        }

        return $iStoreResult;
    }

    // Add init request
    public function addOnInitRequest(string $sTransactionID, array $aOnInitRequest){

        // on_init request data
        $aOnInitRequestData = array(
            'transaction_id' => $sTransactionID,
            'on_init_request' => json_encode($aOnInitRequest)
        );

        $iStoreResult = (new BookingModel)->fAddOnInitRequest($aOnInitRequestData);

        return $iStoreResult;
    }

    // Add confirm request
    public function addConfirmRequest(string $sTransactionID, array $aConfirmRequest){

        // confirm request data
        $aConfirmRequestData = array(
            'transaction_id' => $sTransactionID,
            'confirm_request' => json_encode($aConfirmRequest)
        );

        $iStoreResult = (new BookingModel)->fAddConfirmRequest($aConfirmRequestData);

        return $iStoreResult;
    }

    // Add on_confirm request
    public function addOnConfirmRequest(string $sTransactionID, array $aOnConfirmRequest){

        // on_confirm request data
        $aOnConfirmRequestData = array(
            'transaction_id' => $sTransactionID,
            'on_confirm_request' => json_encode($aOnConfirmRequest)
        );

        $iStoreResult = (new BookingModel)->fAddOnConfirmRequest($aOnConfirmRequestData);

        return $iStoreResult;
    }

    // Get booking request details by message id
    public function getBookingRequestByMessageID(string $sMessageID){
        $oBookingRequest = (new BookingModel)->fGetBookingRequestByMessageID($sMessageID);
        return DataHandler::objectToArray($oBookingRequest);
    }

    // Get booking request details by transaction id
    public function getBookingRequestByTransactionID(string $sTransactionID){
        $oBookingRequest = (new BookingModel)->fGetBookingRequestByTransactionID($sTransactionID);
        return DataHandler::objectToArray($oBookingRequest);
    }
}