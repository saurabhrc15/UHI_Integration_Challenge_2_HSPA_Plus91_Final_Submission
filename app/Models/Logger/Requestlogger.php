<?php

namespace App\Models\Logger;

use Illuminate\Support\Facades\DB;

class Requestlogger{

    // Function to add staff details
    public function fAddRequestLog($aRequestLog){

        $bIQueryResult = DB::table('request_logs')->insert(
            [
                'client_id' => $aRequestLog['client_id'],
                'clinic_id' => $aRequestLog['clinic_id'],
                'url' => $aRequestLog['url'],
                'method' => $aRequestLog['method'],
                'direction' => $aRequestLog['direction'],
                'request_id' => $aRequestLog['request_id'],
                'transaction_id' => $aRequestLog['transaction_id'],
                'request' => $aRequestLog['request'],
                'response' => $aRequestLog['response'],
                'response_code' => $aRequestLog['response_code'],
                'added_on' => $aRequestLog['added_on']
            ]
        );

        return $bIQueryResult? true : false;
    }
}