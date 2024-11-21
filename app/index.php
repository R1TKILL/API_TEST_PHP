<?php

require 'vendor/autoload.php';
require './app/Configs/Env/env.php';

use Slim\Factory\AppFactory;
use App\configLogs\LogConfig;
use App\Auto\BackupDatabase;

$logger = new LogConfig();

try {

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
    
    // * Add the automated backup in API.
    if($dict_ENV['ENV_MODE'] == 'production') {
        $backupScheduler = new BackupDatabase();
        while (true) {
            $backupScheduler->runBackup();
            sleep(60);
        }
    }

    $logger->appLogMsg('INFO', "🚀 API started with success in mode of " . $dict_ENV['ENV_MODE'] . ", running on port " . (string) $dict_ENV['PORT']);
    $app->run();

} catch(Exception $ex) {
    $logger->appLogMsg('CRITICAL', $ex->getMessage());
}
