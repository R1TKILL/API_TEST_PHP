<?php

declare(strict_types=1);
namespace App\Auto;

use App\configLogs\LogConfig;
use Exception;

class BackupDatabase {

    // * Methods:
    private LogConfig $logger;
    private string $backupDir;
    private string $backupFile;
    private string $command;
    private array $dict_ENV;


    // * Constructor:
    public function __construct() {
        $this->logger = new LogConfig();
        $this->dict_ENV = require __DIR__ . '/../../../app/Helpers/LoadEnvironments.php';
        $this->executeBackup();
    }


    // * Methods:
    public function executeBackup() {

        try{

            // * Path and name of backup file:
            $this->backupDir = __DIR__ . "/../../../app/Configs/Database/Backups";
            $this->backupFile = $this->backupDir . $this->dict_ENV['DB_NAME'] . '_' . date('Y-m-d_H-i-s') . '.sql';

            // * Backup command:
            $this->command = ("PGPASSWORD=".$this->dict_ENV['DB_PASS']." pg_dump -U " . $this->dict_ENV['DB_USER'] . " -h " . $this->dict_ENV['DB_HOST'] . " -F c " . $this->dict_ENV['DB_NAME'] . " > $this->backupFile");

            // * Executing the backup:
            exec($this->command, $output, $resultCode);

            if ($resultCode === 0) {
                $this->logger->appLogMsg('INFO', 'Backup performed successfully');
            } 

        } catch(Exception $ex) {
            $this->logger->appLogMsg('CRITICAL', 'Fail in execute a backup: ' . $ex->getMessage());
        }

    }
}

new BackupDatabase();
