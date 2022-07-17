<?php

namespace App\Http\Middleware;

use Closure;

class Request
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $url = $request->fullUrl();
        $oRequestModel = new \App\Models\RequestModel();
        $oRequestModel->url = $url;
        $oRequestModel->request = json_encode([
            'headers' =>  $request->header(),
            'request' => $request->all()
        ]);
        $oRequestModel->response = json_encode($response);
        if(!$oRequestModel->save()){
            throw new \Exception("here");
        }

        $aResponse = json_decode($response->content(), true);
        $sStatusCode = $aResponse['status_code'] ?? $response->status();
        unset($aResponse['status_code']);

        return response($aResponse, $sStatusCode);
    }
}