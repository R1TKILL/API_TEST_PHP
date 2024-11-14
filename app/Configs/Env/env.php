<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;

// * Find and load the .env file.
$envPath = __DIR__ . '/../../../';
$dotenv = Dotenv::createImmutable($envPath);
$dotenv->load();

// * Define the ambient (test, development or production)
$modeApplication = $_ENV['ENV_MODE'] ?: 'development';
$dict_ENV = [];

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
        'DB_PORT' => $_ENV['TEST_DB_PORT'],
        'SMTP_HOST' => $_ENV['SMTP_HOST'],
        'SMTP_SENDER' => $_ENV['SMTP_SENDER'],
        'SMTP_PASS' => $_ENV['SMTP_PASS'],
        'SMTP_PORT' => $_ENV['SMTP_PORT']
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
        'DB_PORT' => $_ENV['DEV_DB_PORT'],
        'SMTP_HOST' => $_ENV['SMTP_HOST'],
        'SMTP_SENDER' => $_ENV['SMTP_SENDER'],
        'SMTP_PASS' => $_ENV['SMTP_PASS'],
        'SMTP_PORT' => $_ENV['SMTP_PORT']
    ];
} else { // * Ambient of Production
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
        'DB_PORT' => $_ENV['PROD_DB_PORT'],
        'SMTP_HOST' => $_ENV['SMTP_HOST'],
        'SMTP_SENDER' => $_ENV['SMTP_SENDER'],
        'SMTP_PASS' => $_ENV['SMTP_PASS'],
        'SMTP_PORT' => $_ENV['SMTP_PORT']
    ];
}
