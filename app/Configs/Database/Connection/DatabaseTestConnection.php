<?php

namespace App\Database;

require_once __DIR__ . "/../../../../vendor/autoload.php";
require './app/Configs/Env/env.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use App\configLogs\LogConfig;
use Doctrine\ORM\ORMSetup;
use Exception;

class DatabaseTestConnection {

    // * Attributes:
    private array $paths;
    private bool $isDevMode;
    private array $dbParams;
    private $metadataConfig;
    private $conn;
    private EntityManager $entityManager;
    private LogConfig $logger;


    // * Constructor:
    public function __construct() {

        try {

            $this->logger = new LogConfig();
       
            // * Keys for Database:
            $this->dbParams = array(
                'driver'      => (string) $_ENV['TEST_DB_DIALECT'],
                'user'        => (string) $_ENV['TEST_DB_USER'],
                'password'    => (string) $_ENV['TEST_DB_PASS'],
                'dbname'      => (string) $_ENV['TEST_DB_NAME'],
                'host'        => (string) $_ENV['TEST_DB_HOST'],   
                'port'        => (string) $_ENV['TEST_DB_PORT'],              
                'sslmode'     => (string) $_ENV['TEST_DB_SSLMODE'], // * Options: disable, allow, prefer, require, verify-ca, verify-full
                'sslrootcert' => (string) $_ENV['TEST_DB_SSLROOTCERT'], // * ssl_ca path, require for verify-ca or verify-full
                'charset'     => (string) $_ENV['TEST_DB_CHARSET'],
                'driverOptions' => [
                    \PDO::ATTR_TIMEOUT => 5 // * Timeout for connection (in seconds)
                ]
            );
    
            // * Config the metadata, devMode and Path for entities (Models):
            $this->metadataConfig = ORMSetup::createAttributeMetadataConfiguration($this->paths = ['app/Models'], $this->isDevMode = true);
            $this->conn = DriverManager::getConnection($this->dbParams, $this->metadataConfig);
            $this->logger->appLogMsg('INFO', 'Connected in database for tests with success!');

        } catch (Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error when trying to connect to the database for tests, error type: ' . $ex->getMessage());
        }

    }

    
    // * Methods:
    public function getConnection(): EntityManager {
        return new EntityManager($this->conn, $this->metadataConfig);
    }

}