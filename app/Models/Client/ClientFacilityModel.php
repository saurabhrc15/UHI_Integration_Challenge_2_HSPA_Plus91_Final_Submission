<?php

namespace App\Models\Client;

use Illuminate\Support\Facades\DB;

class ClientFacilityModel{

    // Function to add new client facility
    public function fAddNewClientFacility($aClientFacitlity){

        $dAddedOn = date("Y-m-d H:i:s");

        $iClientID = DB::table('mxcel_client_facilities')->insertGetId(
            [
                'name' => $aClientFacitlity['name'],
                'clinic_id' => $aClientFacitlity['clinic_id'],
                'client_id' => $aClientFacitlity['client_id'],
                'provider_id' => $aClientFacitlity['provider_id'],
                'added_by' => $aClientFacitlity['added_by'],
                'added_on' => $dAddedOn,
                'deleted' => 0
            ]
        );

        return $iClientID ? $iClientID : 0;
    }

    // Function to update client facility
    public function fUpdateClientFacility($aClientFacitlity, $iClinicID, $iClientID){

        $dUpdatedOn = date("Y-m-d H:i:s");

        $bUQueryResult = DB::table('mxcel_client_facilities')
            ->where('clinic_id', '=', $iClinicID)
            ->where('client_id', '=', $iClientID)
            ->where('deleted', '=', 0)
            ->update(
                [
                    'clinic_id' => $aClientFacitlity['clinic_id'],
                    'name' => $aClientFacitlity['name'],
                    'updated_on' => $dUpdatedOn
                ]
            );

        return $bUQueryResult !== false ? true : false;
    }

    // Function to get client facility details by clinic & client ID
    public function fGetClientFacilityDetails($iClientID, $iClinicID){

        $oQueryBuilder = DB::table('mxcel_client_facilities AS A');

        $oQueryBuilder
            ->leftJoin('mxcel_client AS B', 'A.client_id', '=', 'B.client_id')
            ->select('*')
            ->where('A.client_id', '=', $iClientID)
            ->where('A.clinic_id', '=', $iClinicID)
            ->where('A.deleted', '=', 0)
            ->where('B.deleted', '=', 0)
            ->orderBy('A.facility_id', 'desc');

        $oClientFacility = $oQueryBuilder->get()->first();

        return $oClientFacility;
    }

    // Function to get client details by HIU ID
    public function fGetFacilityByClientKeyAndClinicID($sClientKey, $iClinicID){

        $oQueryBuilder = DB::table('mxcel_client_facilities AS A');

        $oQueryBuilder
            ->leftJoin('mxcel_client AS B', 'A.client_id', '=', 'B.client_id')
            ->select('*')
            ->where('A.clinic_id', '=', $iClinicID)
            ->where('B.client_key', '=', $sClientKey)
            ->where('A.deleted', '=', 0)
            ->where('B.deleted', '=', 0)
            ->orderBy('A.facility_id', 'desc');

        $oClientFacility = $oQueryBuilder->get()->first();

        return $oClientFacility;
    }

    // Function to get client facility details by client and clinic ID
    public function fGetFacilityByClientAndClinicID($iClientID, $iClinicID){

        $oQueryBuilder = DB::table('mxcel_client_facilities AS A');

        $oQueryBuilder
            ->leftJoin('mxcel_client AS B', 'A.client_id', '=', 'B.client_id')
            ->select('*')
            ->where('A.clinic_id', '=', $iClinicID)
            ->where('A.client_id', '=', $iClientID)
            ->where('A.deleted', '=', 0)
            ->where('B.deleted', '=', 0)
            ->orderBy('A.facility_id', 'desc');

        $oClientFacility = $oQueryBuilder->get()->first();

        return $oClientFacility;
    }

    // Function to get all client facilities
    public function fGetAllClientFacilities(){

        $oQueryBuilder = DB::table('mxcel_client_facilities AS A');

        $oQueryBuilder
            ->leftJoin('mxcel_client AS B', 'A.client_id', '=', 'B.client_id')
            ->select('*')
            ->where('A.deleted', '=', 0)
            ->where('B.deleted', '=', 0);

        $oClientFacility = $oQueryBuilder->get();

        return $oClientFacility;
    }
}