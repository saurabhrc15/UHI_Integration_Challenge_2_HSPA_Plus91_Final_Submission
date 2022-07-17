<?php

namespace App\Models\Fulfillment;

use Illuminate\Support\Facades\DB;

class FulfillmentModel{

    // Function to add status request
    public function fAddStatusRequest($aStatusRequest){

        $bIQueryResult = DB::table('uhi_fulfillment_requests')->insert(
            [
                'message_id' => $aStatusRequest['message_id'],
                'transaction_id' => $aStatusRequest['transaction_id'],
                'clinic_id' => $aStatusRequest['clinic_id'],
                'client_id' => $aStatusRequest['client_id'],
                'status_request' => $aStatusRequest['status_request'],
                'added_on' => $aStatusRequest['added_on']
            ]
        );
        
        return $bIQueryResult ? true : false;
    }

    // Function to add on_status request
    public function fAddOnStatusRequest($aOnStatusRequest){

        $bUQueryResult = DB::table('uhi_fulfillment_requests')
            ->where('message_id', '=', $aOnStatusRequest['message_id'])
            ->update(
                [
                    'on_status_request' => $aOnStatusRequest['on_status_request']
                ]
            );

        return $bUQueryResult !== false ? true : false;
    }
}