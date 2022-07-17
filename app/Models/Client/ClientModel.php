<?php

namespace App\Models\Client;

use Illuminate\Support\Facades\DB;

class ClientModel{

    // Function to add new client
    public function fAddNewClient($aClientDetails){

        $iClientID = DB::table('mxcel_client')->insertGetId(
            [
                'client_name' => $aClientDetails['client_name'],
                'client_url' => $aClientDetails['client_url'],
                'client_key' => $aClientDetails['client_key'],
                'client_secret' => $aClientDetails['client_secret'],
                'deleted' => 0
            ]
        );

        return $iClientID ? $iClientID : 0;
    }

    // Function to get client details by client ID
    public function fGetClientDetailsByClientID($iClientID){

        $oClientDetails = DB::table('mxcel_client')
            ->select('*')
            ->where('client_id', '=', $iClientID)
            ->where('deleted', '=', 0)
            ->get()
            ->first();

        return $oClientDetails;
    }

    // Function to delete client
    public function fDeleteClient(int $sClientKey){

        $bUQueryResult = DB::table('mxcel_client')
            ->where('client_key', '=', $sClientKey)
            ->where('deleted', '=', 0)
            ->update(
                [
                    'deleted' => 1
                ]
            );

        return $bUQueryResult ? true : false;
    }

    // Function to get client details by client key
    public function fGetClientDetailsByClientKey($sClientKey){

        $oClientDetails = DB::table('mxcel_client')
            ->select('*')
            ->where('client_key', '=', $sClientKey)
            ->where('deleted', '=', 0)
            ->get()
            ->first();

        return $oClientDetails;
    }
}