<?php

require './app/Configs/Env/env.php';

use App\configLogs\LogConfig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

$logger = new LogConfig();
$CORS_Origin = (string) $dict_ENV['ORIGIN_ADDRESS'] ?: '*';

return function ($app) use ($CORS_Origin, $logger) {
    $app->add(function (Request $request, RequestHandler $handler) use ($CORS_Origin, $logger): Response {
        try {
            $response = $handler->handle($request);
            $response = $response->withHeader('Access-Control-Allow-Origin', $CORS_Origin);
            $response = $response->withHeader('Access-Control-Allow-Headers', 'Content-Type');
            $response = $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            return $response;
        } catch(Exception $ex) {
            $logger->appLogMsg('ERROR', 'Error in middleware, type error: ' . $ex->getMessage());
        }
    });
};
