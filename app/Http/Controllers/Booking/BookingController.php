<?php

namespace App\Http\Controllers\Booking;

use Illuminate\Http\Request;
use App\Services\Booking\BookingRequest;
use App\Http\Controllers\Controller;

class BookingController extends Controller{

    // Booking select request
    public function select(Request $request){

        $oResponse = (new BookingRequest)
            ->selectRequest($request->all());

        return $oResponse;
    }

    // Booking init request
    public function init(Request $request){

        $oResponse = (new BookingRequest)
            ->initRequest($request->all());

        return $oResponse;
    }

    // Booking confirm request
    public function confirm(Request $request){

        $oResponse = (new BookingRequest)
            ->confirmRequest($request->all());

        return $oResponse;
    }

}