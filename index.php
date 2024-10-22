<?php

require 'vendor/autoload.php';
require 'app/Configs/Env/env.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// * Add the middlewares in API.
$middlewares = require './app/Middlewares/middlewares.php';
$middlewares($app);
// * Add the routes in API.
$routes = require './app/Router/routes.php';
$routes($app);

$app->run();
