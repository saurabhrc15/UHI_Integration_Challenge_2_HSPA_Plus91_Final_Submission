<?php

namespace App\Repositories\Client;

use App\Models\Client\ClientFacilityModel as ClientFacilityModel;
use App\Services\API\DataHandler as DataHandler;

class ClientFacilityRepo{

    // Function to get facility details by client key & clinic ID
    public function getFacilityByClientKeyAndClinicID(string $sClientKey, int $iClinicID){
        $oClientDetails = (new ClientFacilityModel)->fGetFacilityByClientKeyAndClinicID($sClientKey, $iClinicID);
        $aClientDetails = DataHandler::objectToArray($oClientDetails);
        return $aClientDetails;
    }

    // Function to get facility details by client and clinic ID
    public function getFacilityByClientAndClinicID(int $iClientID, int $iClinicID){
        $oClientDetails = (new ClientFacilityModel)->fGetFacilityByClientAndClinicID($iClientID, $iClinicID);
        $aClientDetails = DataHandler::objectToArray($oClientDetails);
        return $aClientDetails;
    }

    // Function to get all medixcel client facilities
    public function getAllClientFacilities(){
        $oClientFacilities = (new ClientFacilityModel)->fGetAllClientFacilities();
        return DataHandler::objectToArray($oClientFacilities);
    }

    // Funtion to update the facility details
    public function updateClientFacility(array $aRequest){

        $iClinicID = (int) $aRequest['clinic_id'];
        $iClientID = config('medixcel.client.client_id');

        //check if locker exist
        $oClientFacilityModel = (new ClientFacilityModel);
        $oClientDetails = $oClientFacilityModel->fGetClientFacilityDetails(1, $iClinicID);
        $iFacilityID = $oClientDetails->facility_id ?? null;

        if($oClientDetails){

            $iClinicID = $oClientDetails->clinic_id;
            $bResult = $oClientFacilityModel->fUpdateClientFacility($aRequest, $iClinicID, $iClientID);

        }else{

            $aRequest['client_id'] = $iClientID;
            $bResult = $oClientFacilityModel->fAddNewClientFacility($aRequest);

        }

        if($bResult){
            $aResponse = ['error' => false, 'message' => "Health facility updated successfully."];
        }else{
            $aResponse = ['error' => true, 'message' => "Unknown error. Please try again after sometime."];
        }

        return $aResponse;

    }
}