<?php

namespace App\Http\Controllers\Fulfillment;

use Illuminate\Http\Request;
use App\Services\Fulfillment\FulfillmentRequest;
use App\Http\Controllers\Controller;

class FulfillmentController extends Controller{

    // Fulfillment status request
    public function status(Request $request){

        $oResponse = (new FulfillmentRequest)
            ->statusRequest($request->all());

        return $oResponse;
    }
}