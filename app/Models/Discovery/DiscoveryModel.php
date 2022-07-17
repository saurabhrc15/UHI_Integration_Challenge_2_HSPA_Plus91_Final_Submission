<?php

namespace App\Models\Discovery;

use Illuminate\Support\Facades\DB;

class DiscoveryModel{

    // Function to discovery request
    public function fAddSearchRequest($aRequest){

        $bIQueryResult = DB::table('uhi_search_requests')->insert(
            [
                'message_id' => $aRequest['message_id'],
                'transaction_id' => $aRequest['transaction_id'],
                'search_request' => $aRequest['search_request'],
                'added_on' => $aRequest['added_on']
            ]
        );
        
        return $bIQueryResult ? true : false;
    }

    // Function to get subscription request by request ID
    public function fGetSearchRequestByMessageID($sMessageID){

        $oSearchRequest = DB::table('uhi_search_requests')
            ->select('*')
            ->where('message_id', '=', $sMessageID)
            ->get()
            ->first();

        return $oSearchRequest;
    }

    // Function to get all search requests
    public function fGetPendingSearchRequests(){

        $sCallbackStatus = "pending";

        $oSearchRequest = DB::table('uhi_search_requests')
            ->select('*')
            ->where('callback_status', '=', $sCallbackStatus)
            ->get();

        return $oSearchRequest;
    }

    // Function to medixcel discovery request
    public function fAddSearchResponseDetails($aRequest){

        $bIQueryResult = DB::table('uhi_medixcel_search_response')->insert(
            [
                'message_id' => $aRequest['message_id'],
                'transaction_id' => $aRequest['transaction_id'],
                'client_id' => $aRequest['client_id'],
                'clinic_id' => $aRequest['clinic_id'],
                'search_request_to_medixcel' => $aRequest['search_request_to_medixcel'],
                'search_response_from_medixcel' => $aRequest['search_response_from_medixcel'],
                'on_search_request' => $aRequest['on_search_request'],
                'added_on' => $aRequest['added_on']
            ]
        );
        
        return $bIQueryResult ? true : false;
    }

    // Function to update the status of search request callback status
    public function fUpdateSeachRequestCallbackStatus($sMessageID, $sCallbackStatus){

        $bUQueryResult = DB::table('uhi_search_requests')
            ->where('message_id', '=', $sMessageID)
            ->update(
                [
                    'callback_status' => $sCallbackStatus
                ]
            );

        return $bUQueryResult !== false ? true : false;
    }

    // Function to get client details by message and clinic ID
    public function fGetClientByMessageAndClinicID($sMessageID, $iClinicID){

        $oSearchRequest = DB::table('uhi_medixcel_search_response')
            ->select('*')
            ->where('message_id', '=', $sMessageID)
            ->where('clinic_id', '=', $iClinicID)
            ->orderBy('id', 'desc')
            ->get()
            ->first();

        return $oSearchRequest;
    }

    // Function to get client details by message and clinic ID
    public function fGetClientByTransactionAndClinicID($sTransactionID, $iClinicID){

        $oSearchRequest = DB::table('uhi_medixcel_search_response')
            ->select('*')
            ->where('transaction_id', '=', $sTransactionID)
            ->where('clinic_id', '=', $iClinicID)
            ->orderBy('id', 'desc')
            ->get()
            ->first();

        return $oSearchRequest;
    }

    // Function to get discovery request by transaction_id
    public function fGetDiscoveryRequestByTransactionID($sTransactionID){

        $oSearchRequest = DB::table('uhi_medixcel_search_response')
            ->select('*')
            ->where('transaction_id', '=', $sTransactionID)
            ->orderBy('id', 'desc')
            ->get()
            ->first();

        return $oSearchRequest;
    }
}