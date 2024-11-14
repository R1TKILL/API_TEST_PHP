<?php

namespace App\Database;

require_once __DIR__ . "/../../../../vendor/autoload.php";
require './app/Configs/Env/env.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class DatabaseConnection {

    // * Attributes:
    private array $paths;
    private bool $isDevMode;
    private array $dbParams;
    private $metadataConfig;
    private $conn;
    private EntityManager $entityManager;

    // * Constructor:
    public function __construct() {

        global $dict_ENV;
   
        // * Keys for Database:
        $this->dbParams = array(
            'driver'   => (string) $dict_ENV['DB_DIALECT'],
            'user'     => (string) $dict_ENV['DB_USER'],
            'password' => (string) $dict_ENV['DB_PASS'],
            'dbname'   => (string) $dict_ENV['DB_NAME'],
            'host'     => (string) $dict_ENV['DB_HOST'],   
            'port'     => (string) $dict_ENV['DB_PORT'],              
            'charset'  => 'utf8mb4',          
            'driverOptions' => [
                \PDO::ATTR_TIMEOUT => 5 // * Timeout for connection (in seconds)
            ]
        );

        // * Config the metadata, devMode and Path for entities (Models):
        $this->metadataConfig = ORMSetup::createAttributeMetadataConfiguration($this->paths = [__DIR__ . '/../../Models'], $this->isDevMode = true);
        $this->conn = DriverManager::getConnection($this->dbParams, $this->metadataConfig);

    }

    // * Methods:

    public function getConnection(): EntityManager {
        return new EntityManager($this->conn, $this->metadataConfig);
    }

}
