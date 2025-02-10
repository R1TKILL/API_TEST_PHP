<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../app/Configs/Env/env.php';

use Slim\Factory\AppFactory;
use App\configLogs\LogConfig;

$logger = new LogConfig();

try {

    $app = AppFactory::create();
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware($dict_ENV['ERROR_DETAILS'] ?: false, true, true);

    // * Add the middlewares in API.
    $middlewares = require __DIR__ . '/../../app/Middlewares/middlewares.php';
    $middlewares($app);

    // * Add the routes in API.
    $routes = require __DIR__ . '/../../app/Router/routes.php';
    $routes($app);

    $logger->appLogMsg('INFO', "🚀 API started with success in mode of " . $dict_ENV['ENV_MODE'] . ", running on port " . (string) $dict_ENV['PORT']);
    $app->run();

} catch(Exception $ex) {
    $logger->appLogMsg('CRITICAL', $ex->getMessage());
}
