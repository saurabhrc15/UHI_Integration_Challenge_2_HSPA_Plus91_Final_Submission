<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    //return $router->app->version();
    return 'This is Medixcel UHI Microservice.';
});

$router->get('welcome', function () {
    return 'This is Medixcel UHI Microservice.';
});

// UHI APIs
$router->group(['middleware' => 'GatewayToHSPARequests'], function($router){
    $router->post('/search', 'Discovery\DiscoveryController@search');
    //$router->post('/api/v1/on_search', 'Discovery\DiscoveryController@onSearch');

    $router->post('/select', 'Booking\BookingController@select');
    //$router->post('/on_select', 'Booking\BookingController@onSelect');

    $router->post('/init', 'Booking\BookingController@init');
    //$router->post('/on_init', 'Booking\BookingController@onInit');

    $router->post('/confirm', 'Booking\BookingController@confirm');
    //$router->post('/on_confirm', 'Booking\BookingController@onConfirm');

    $router->post('/status', 'Fulfillment\FulfillmentController@status');
    //$router->post('/on_status', 'Fulfillment\FulfillmentController@onStatus');
});