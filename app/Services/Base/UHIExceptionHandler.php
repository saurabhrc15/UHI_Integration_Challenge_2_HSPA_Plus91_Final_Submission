<?php

namespace App\Services\Base;

class UHIExceptionHandler{

    // Function to handle internal server error
    public static function handleInternalServerException($exception){

        $aErrorResponse = [];
        $aErrorDetails = ['error' => true, 'exception' => $exception->getMessage(), 'error_at' => 'locker_service'];

        if ($exception->hasResponse()) {

            $response = $exception->getResponse();
            $aResponseBody = json_decode($response->getBody()->getContents(), true);
            $aResponseBody['status_code'] = $response->getStatusCode();

            // Error details
            $aErrorDetails['status_code'] = $response->getStatusCode();
            $aErrorDetails['reason_phrase'] = $response->getReasonPhrase();
            $aErrorDetails['message'] = $aResponseBody['message'] ?? 'Something went wrong';
            $aErrorResponse = $aResponseBody;

        }else{
            $aErrorDetails['message'] = 'Something went wrong';
            $aErrorDetails['status_code'] = 500;
        }

        $aErrorResponse['error_details'] = $aErrorDetails;

        return $aErrorResponse;
    }

    // Function to handle general exceptions
    public static function handleGeneralExceptions($exception){

        $aErrorResponse = [];
        $aErrorResponse['error_details'] = [
            'error' => true,
            'exception' => $exception->getMessage(),
            'error_at' => 'locker_service',
            'message' => 'Something went wrong',
            'status_code' => 500
        ];

        return $aErrorResponse;
    }

    // Function to handle client server error
    public static function handleClientException($exception){

        $aErrorResponse = [];
        $aErrorDetails = ['error' => true, 'exception' => $exception->getMessage(), 'error_at' => 'external_service'];

        if ($exception->hasResponse()) {

            $response = $exception->getResponse();
            $aResponseBody = json_decode($response->getBody()->getContents(), true);
            $aResponseBody['status_code'] = $response->getStatusCode();

            // Error details
            $aErrorDetails['status_code'] = $response->getStatusCode();
            $aErrorDetails['reason_phrase'] = $response->getReasonPhrase();
            $aErrorDetails['message'] = $aResponseBody['message'] ?? 'Something went wrong';

            $aErrorResponse = $aResponseBody;

        }else{

            $aErrorDetails['message'] = 'Something went wrong';
            $aErrorDetails['status_code'] = 500;

        }

        $aErrorResponse['error_details'] = $aErrorDetails;

        return $aErrorResponse;
    }

    // Function to handle client server error
    public static function handleClientConnectException($exception){

        $aErrorResponse = [];

        $aErrorDetails = [
            'error' => true,
            'message' => 'There was error while connecting to external service, please try again in sometime',
            'exception' => $exception->getMessage(),
            'status_code' => 504,
            'error_at' => 'external_service'
        ];

        $aErrorResponse['error_details'] = $aErrorDetails;
        $aErrorResponse['status_code'] = 504;

        return $aErrorResponse;
    }
}