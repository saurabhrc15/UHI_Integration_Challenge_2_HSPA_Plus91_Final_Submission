<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Client\ClientController;

class CreateClientCommand extends Command
{
    protected $signature = 'client:create {--client_name=} {--client_url=}';
    # example :- php artisan client:create --client_name="localhost" --client_url="http://localhost/medixcel-base/base"

    protected $description = 'Create a new client';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $sClientName = $this->option('client_name');
        $sClientURL = $this->option('client_url');
        
        $oClientModel = ClientController::fCreateClient($sClientName, $sClientURL);

        if (!$oClientModel) {
            $this->error('Error wile creating new client');
        } else {
            $this->info("Client Name: ".$oClientModel->client_name);
            $this->info("Client Key: ".$oClientModel->client_key);
            $this->info("Client Secret: ".$oClientModel->client_secret);
        }
    }
}
