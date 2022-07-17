<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Discovery\DiscoveryRequest;

class ProcessSearchRequests extends Command
{
    protected $signature = 'hspa:process-search-requests';
    protected $description = 'Command To Process search requests to HSPA';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        (new DiscoveryRequest)->onSearchRequest();
    }
}
