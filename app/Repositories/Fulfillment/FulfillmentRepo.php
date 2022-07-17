<?php

namespace App\Repositories\Fulfillment;

use App\Models\Fulfillment\FulfillmentModel as FulfillmentModel;
use App\Services\API\DataHandler as DataHandler;

class FulfillmentRepo{

    // Add status request
    public function addStatusRequest(string $sMessageID, string $sTransactionID, int $iClientID, int $iClinicID, array $aStatusRequest){

        // Status request data
        $aStatusRequestData = array(
            'message_id' => $sMessageID,
            'transaction_id' => $sTransactionID,
            'clinic_id' => $iClinicID,
            'client_id' => $iClientID,
            'status_request' => json_encode($aStatusRequest),
            'added_on' => date("Y-m-d H:i:s")
        );

        $iStoreResult = (new FulfillmentModel)->fAddStatusRequest($aStatusRequestData);

        return $iStoreResult;
    }

    // Add on_select request
    public function addOnStatusRequest(string $sMessageID, array $aOnStatusrequest){

        // on_status request data
        $aOnStatusRequestData = array(
            'message_id' => $sMessageID,
            'on_status_request' => json_encode($aOnStatusrequest)
        );

        $iStoreResult = (new FulfillmentModel)->fAddOnStatusRequest($aOnStatusRequestData);

        return $iStoreResult;
    }
}