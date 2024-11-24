<?php

namespace App\Auto;

require './app/Configs/Env/env.php';

use App\configLogs\LogConfig;
use Exception;

class BackupDatabase {

    // * Methods:
    private LogConfig $logger;
    private string $backupDir;
    private string $backupFile;
    private string $command;


    // * Constructor:
    public function __construct() {
        $this->logger = new LogConfig();
        $this->executeBackup();
    }


    // * Methods:
    public function executeBackup() {

        try{

            global $dict_ENV;

            // * Path and name of backup file:
            $this->backupDir = __DIR__ . "/../../../app/Configs/Database/Backups";
            $this->backupFile = $this->backupDir . $dict_ENV['DB_NAME'] . '_' . date('Y-m-d_H-i-s') . '.sql';

            // * Backup command:
            $this->command = ("PGPASSWORD=".$dict_ENV['DB_PASS']." pg_dump -U " . $dict_ENV['DB_USER'] . " -h " . $dict_ENV['DB_HOST'] . " -F c " . $dict_ENV['DB_NAME'] . " > $this->backupFile");

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
