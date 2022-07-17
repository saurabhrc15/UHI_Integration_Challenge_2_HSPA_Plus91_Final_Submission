<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Client\ClientController;

class DeleteClientCommand extends Command
{

    protected $signature = 'client:delete {--client_key=} ';

    protected $description = 'Delete an existing client by client key';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $sClientKey = $this->option('client_key');
        $result = ClientController::fDeleteClient($sClientKey);
        if (!$result) {
            $this->error('Error wile deleting client');
        } else {
            $this->info('Client deleted successfully!');
        }
    }
}
