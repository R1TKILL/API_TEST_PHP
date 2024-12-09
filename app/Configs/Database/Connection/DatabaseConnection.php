<?php

namespace App\Database;

require_once __DIR__ . "/../../../../vendor/autoload.php";
require './app/Configs/Env/env.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use App\configLogs\LogConfig;
use Doctrine\ORM\ORMSetup;
use Exception;

class DatabaseConnection {

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

            global $dict_ENV;
            $this->logger = new LogConfig();
       
            // * Keys for Database:
            $this->dbParams = array(
                'driver'      => (string) $dict_ENV['DB_DIALECT'],
                'user'        => (string) $dict_ENV['DB_USER'],
                'password'    => (string) $dict_ENV['DB_PASS'],
                'dbname'      => (string) $dict_ENV['DB_NAME'],
                'host'        => (string) $dict_ENV['DB_HOST'],   
                'port'        => (string) $dict_ENV['DB_PORT'],              
                'sslmode'     => (string) $dict_ENV['DB_SSLMODE'], // * Options: disable, allow, prefer, require, verify-ca, verify-full
                'sslrootcert' => (string) $dict_ENV['DB_SSLROOTCERT'], // * ssl_ca path, require for verify-ca or verify-full
                'charset'     => (string) $dict_ENV['DB_CHARSET'],
                'driverOptions' => [
                    \PDO::ATTR_TIMEOUT => 5 // * Timeout for connection (in seconds)
                ]
            );
    
            // * Config the metadata, devMode and Path for entities (Models):
            $this->metadataConfig = ORMSetup::createAttributeMetadataConfiguration($this->paths = [__DIR__ . '/../../Models'], $this->isDevMode = true);
            $this->conn = DriverManager::getConnection($this->dbParams, $this->metadataConfig);
            $this->logger->appLogMsg('INFO', 'Connected in database with success');

        } catch (Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error when trying to connect to the database, error type: ' . $ex->getMessage());
        }

    }

    
    // * Methods:
    public function getConnection(): EntityManager {
        return new EntityManager($this->conn, $this->metadataConfig);
    }

}
