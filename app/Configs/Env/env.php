<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;

$envPath = __DIR__ . '/../../../';
$dict_ENV = [];

$dotenv = Dotenv::createImmutable($envPath);
$dotenv->load();


// * Define the ambient (test, development or production
$modeApplication = $_ENV['ENV_MODE'] ?: 'development';

// * Loads the variables according to the environment.
if ($modeApplication == 'test') {
    $dict_ENV = [
        'ENV_MODE' => $_ENV['ENV_MODE'],
        'ORIGIN_ADDRESS' => $_ENV['TEST_ORIGIN_ADDRESS'],
        'PORT' => $_ENV['TEST_PORT'],
        'DEBUG_MODE' => $_ENV['TEST_DEBUG_MODE'],
        'PREFIX_API' => $_ENV['URL_API_PREFIX'],
        'DB_DIALECT' => $_ENV['TEST_DB_DIALECT'],
        'DB_USER' => $_ENV['TEST_DB_USER'],
        'DB_PASS' => $_ENV['TEST_DB_PASS'],
        'DB_NAME' => $_ENV['TEST_DB_NAME'],
        'DB_HOST' => $_ENV['TEST_DB_HOST'],
        'DB_PORT' => $_ENV['TEST_DB_PORT']
    ];
} elseif ($modeApplication == 'development') {
    $dict_ENV = [
        'ENV_MODE' => $_ENV['ENV_MODE'],
        'ORIGIN_ADDRESS' => $_ENV['DEV_ORIGIN_ADDRESS'],
        'PORT' => $_ENV['DEV_PORT'],
        'DEBUG_MODE' => $_ENV['DEV_DEBUG_MODE'],
        'PREFIX_API' => $_ENV['URL_API_PREFIX'],
        'DB_DIALECT' => $_ENV['DEV_DB_DIALECT'],
        'DB_USER' => $_ENV['DEV_DB_USER'],
        'DB_PASS' => $_ENV['DEV_DB_PASS'],
        'DB_NAME' => $_ENV['DEV_DB_NAME'],
        'DB_HOST' => $_ENV['DEV_DB_HOST'],
        'DB_PORT' => $_ENV['DEV_DB_PORT']
    ];
} else { // Ambient of Production
    $dict_ENV = [
        'ENV_MODE' => $_ENV['ENV_MODE'],
        'ORIGIN_ADDRESS' => $_ENV['PROD_ORIGIN_ADDRESS'],
        'PORT' => $_ENV['PROD_PORT'],
        'DEBUG_MODE' => $_ENV['PROD_DEBUG_MODE'],
        'PREFIX_API' => $_ENV['URL_API_PREFIX'],
        'DB_DIALECT' => $_ENV['PROD_DB_DIALECT'],
        'DB_USER' => $_ENV['PROD_DB_USER'],
        'DB_PASS' => $_ENV['PROD_DB_PASS'],
        'DB_NAME' => $_ENV['PROD_DB_NAME'],
        'DB_HOST' => $_ENV['PROD_DB_HOST'],
        'DB_PORT' => $_ENV['PROD_DB_PORT']
    ];
}
