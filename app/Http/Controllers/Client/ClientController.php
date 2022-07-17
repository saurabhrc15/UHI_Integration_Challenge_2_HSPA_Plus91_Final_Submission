<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\ClientModel;
use App\Repositories\Client\ClientRepo as ClientRepo;

class ClientController extends Controller
{
    // function to create client
    public static function fCreateClient(string $sClientName, string $sClientURL)
    {

        if ($sClientName == '' || $sClientURL == '') {
            return false;
        }

        try{
            return (new ClientRepo)->fCreateClient($sClientName, $sClientURL);
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function fDeleteClient(string $sClientKey)
    {
        if (strlen($sClientKey) > 0) {
            return (new ClientRepo)->fDeleteClient($sClientKey);
        } else {
            return false;
        }
    }

    public static function fGetClientByClientID(int $iClientID)
    {
        return (new ClientRepo)->fGetClientByClientID($iClientID);
    }

    public static function fGetClientByClientKey(string $sClientKey)
    {
        return (new ClientRepo)->fGetClientByClientKey($sClientKey);
    }
}
