<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\Discovery\DiscoveryRequest;

class AfterSearchResponseWorker
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $relay = (new DiscoveryRequest)->onSearchRequest($request);
        $relay->relay();
    }
}