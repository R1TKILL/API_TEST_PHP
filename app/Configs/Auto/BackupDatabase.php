<?php

namespace App\Auto;

require './app/Configs/Env/env.php';

use App\configLogs\LogConfig;
use Exception;

class BackupDatabase {

    // * Methods:
    private LogConfig $logger;
    private string $currentDay;
    private string $currentHour;
    private string $backupDir;
    private string $backupFile;
    private string $command;


    // * Constructor:
    public function runBackup() {

        $this->logger = new LogConfig();

        // * Receive the date in hour in number format:
        $this->currentDay = date('w');  // * 0 = Sunday, 1 = Monday, ..., 5 = Friday
        $this->currentHour = date('H'); // * Hour in 24h format

        // * Verify if is the Friday at 23:00:
        if ($this->currentDay == 5 && $this->currentHour == 23) {
            $this->executeBackup();
        }

    }


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
