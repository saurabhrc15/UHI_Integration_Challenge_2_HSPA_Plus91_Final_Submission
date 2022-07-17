<?php

namespace App\Models\Booking;

use Illuminate\Support\Facades\DB;

class BookingModel{

    // Function to add select request
    public function fAddSelectRequest($aRequest){

        $bIQueryResult = DB::table('uhi_booking_requests')->insert(
            [
                'message_id' => $aRequest['message_id'],
                'client_id' => $aRequest['client_id'],
                'clinic_id' => $aRequest['clinic_id'],
                'transaction_id' => $aRequest['transaction_id'],
                'select_request' => $aRequest['select_request'],
                'added_on' => $aRequest['added_on']
            ]
        );
        
        return $bIQueryResult ? true : false;
    }

    // Function to add on_select request
    public function fAddOnSelectRequest($aOnSelectRequestData){

        $bUQueryResult = DB::table('uhi_booking_requests')
            ->where('transaction_id', '=', $aOnSelectRequestData['transaction_id'])
            ->update(
                [
                    'on_select_request' => $aOnSelectRequestData['on_select_request']
                ]
            );

        return $bUQueryResult !== false ? true : false;
    }

    // Function to add init request
    public function fAddInitRequest($aInitRequestData){

        $bIQueryResult = DB::table('uhi_booking_requests')->insert(
            [
                'message_id' => $aInitRequestData['message_id'],
                'client_id' => $aInitRequestData['client_id'],
                'clinic_id' => $aInitRequestData['clinic_id'],
                'transaction_id' => $aInitRequestData['transaction_id'],
                'init_request' => $aInitRequestData['init_request'],
                'added_on' => $aInitRequestData['added_on']
            ]
        );
        
        return $bIQueryResult ? true : false;
    }

    // Function to add init request
    public function fUpdateInitRequest($aInitRequestData){

        $bUQueryResult = DB::table('uhi_booking_requests')
            ->where('transaction_id', '=', $aInitRequestData['transaction_id'])
            ->update(
                [
                    'init_request' => $aInitRequestData['init_request']
                ]
            );

        return $bUQueryResult !== false ? true : false;
    }

    // Function to add on_init request
    public function fAddOnInitRequest($aOnInitRequestData){

        $bUQueryResult = DB::table('uhi_booking_requests')
            ->where('transaction_id', '=', $aOnInitRequestData['transaction_id'])
            ->update(
                [
                    'on_init_request' => $aOnInitRequestData['on_init_request']
                ]
            );

        return $bUQueryResult !== false ? true : false;
    }

    // Function to add confirm request
    public function fAddConfirmRequest($aConfirmRequestData){

        $bUQueryResult = DB::table('uhi_booking_requests')
            ->where('transaction_id', '=', $aConfirmRequestData['transaction_id'])
            ->update(
                [
                    'confirm_request' => $aConfirmRequestData['confirm_request']
                ]
            );

        return $bUQueryResult !== false ? true : false;
    }

    // Function to add on_confirm request
    public function fAddOnConfirmRequest($aOnConfirmRequestData){

        $bUQueryResult = DB::table('uhi_booking_requests')
            ->where('transaction_id', '=', $aOnConfirmRequestData['transaction_id'])
            ->update(
                [
                    'on_confirm_request' => $aOnConfirmRequestData['on_confirm_request']
                ]
            );

        return $bUQueryResult !== false ? true : false;
    }

    // Function to get client & clinic ID
    public function fGetBookingRequestByMessageID($sMessageID){

        $oBookingRequest = DB::table('uhi_booking_requests')
            ->select('*')
            ->where('message_id', '=', $sMessageID)
            ->orderBy('id', 'desc')
            ->get()
            ->first();

        return $oBookingRequest;
    }

    // Function to get booking request by transaction ID
    public function fGetBookingRequestByTransactionID($sTransactionID){

        $oBookingRequest = DB::table('uhi_booking_requests')
            ->select('*')
            ->where('transaction_id', '=', $sTransactionID)
            ->orderBy('id', 'desc')
            ->get()
            ->first();

        return $oBookingRequest;
    }
}