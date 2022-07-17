<?php

namespace App\Http\Controllers\Discovery;

use Illuminate\Http\Request;
use App\Services\Discovery\DiscoveryRequest;
use App\Http\Controllers\Controller;

class DiscoveryController extends Controller{

    // Search request
    public function search(Request $request){

        $oResponse = (new DiscoveryRequest)
            ->searchRequest($request->all());

        return $oResponse;
    }

}