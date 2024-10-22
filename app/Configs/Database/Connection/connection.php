<?php

require_once __DIR__ . "/../../../../vendor/autoload.php";
require './app/Configs/Env/env.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

// * Path for entities (Models)
$paths = [__DIR__ . '/../../Models'];
$isDevMode = true;

// * Dates of Database
$dbParams = array(
    'driver'   => (string) $dict_ENV['DB_DIALECT'],
    'user'     => (string) $dict_ENV['DB_USER'],
    'password' => (string) $dict_ENV['DB_PASS'],
    'dbname'   => (string) $dict_ENV['DB_NAME'],
    'host'     => (string) $dict_ENV['DB_HOST'],   
    'port'     => (string) $dict_ENV['DB_PORT'],              
    'charset'  => 'utf8mb4',          
    'driverOptions' => [
        PDO::ATTR_TIMEOUT => 5 // * Timeout for connection (in seconds)
    ]
);

// * Config the EntityManager.
$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$conn = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($conn, $config);


// * Commands for use in migrations, run the command in bash in root folder of project.
// generate: vendor/bin/doctrine-migrations generate --configuration=migrations-config.php
// list migrates: vendor/bin/doctrine-migrations status --configuration=migrations-config.php --db-configuration=app/Configs/Database/Connection/connection.php

// * in first migration use: vendor/bin/doctrine-migrations migrate --configuration=migrations-config.php
// migrate: vendor/bin/doctrine-migrations migrate <version> --configuration=migrations-config.php --db-configuration=app/Configs/Database/Connection/connection.php
// rollback: vendor/bin/doctrine-migrations rollback <version> --configuration=migrations-config.php --db-configuration=app/Configs/Database/Connection/connection.php
