<?php
require_once __DIR__.'/../../config/configEhr.php';

use Plus91\Medixcel\FHIR\API\FHIRPatient;
use Plus91\Medixcel\FHIR\API\FHIRAppointment;
use Plus91\Medixcel\HSPA\API\HSPABookingAPI;
use Plus91\Medixcel\HSPA\API\HSPASearchAPI;
use Ramsey\Uuid\Uuid;

$app = new Slim\App(new \Slim\Container([
    'settings' => [
        'displayErrorDetails' => true
    ],
]));

//! Middlware for checking access token..
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "secret" => HSPA_INTEGRATION_CLIENT_SECRET,
    "algorithm"=>['HS256'],
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));

$app->post('/search', function($request, $response, $args) {
	
	return $response->withHeader('Content-type', 'application/json')->withJson(
        (new HSPASearchAPI)->search($request->getParsedBody())
    );
});

$app->post('/select', function($request, $response, $args) {

    return $response->withHeader('Content-type', 'application/json')->withJson(
        (new HSPABookingAPI)->select($request->getParsedBody())
    );
});

$app->post('/init', function($request, $response, $args) {

    return $response->withHeader('Content-type', 'application/json')->withJson(
        (new HSPABookingAPI)->init($request->getParsedBody())
    );
});

$app->post('/confirm', function($request, $response, $args) {

	return $response->withHeader('Content-type', 'application/json')->withJson(
        (new HSPABookingAPI)->confirm($request->getParsedBody())
    );
});

$app->post('/status', function($request, $response, $args) {
	
	return $response->withHeader('Content-type', 'application/json')->withJson(
        (new HSPABookingAPI)->status($request->getParsedBody())
    );
});

$app->run();