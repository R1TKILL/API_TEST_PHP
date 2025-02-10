<?php

require __DIR__ . '/../../app/Configs/Env/env.php';

use App\configLogs\LogConfig;
use App\Middlewares\CorsMiddleware;
use App\Middlewares\CacheMiddleware;
use App\Middlewares\SessionMiddleware;

$logger = new LogConfig();
$corsOrigin = $dict_ENV['ORIGIN_ADDRESS'] ?? '*';

return function ($app) use ($corsOrigin, $logger) {
    try{
        $app->add(new SessionMiddleware());
        $app->add(new CacheMiddleware());
        $app->add(new CorsMiddleware($corsOrigin));
    } catch (Exception $ex) {
        $logger->appLogMsg('ERROR', 'Error in middleware, type error: ' . $ex->getMessage());
        $response = new \Slim\Psr7\Response();
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500, 'Internal Server Error.');
    }
};
