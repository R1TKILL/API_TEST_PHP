<?php

require './app/Configs/Env/env.php';

use App\configLogs\LogConfig;

$logger = new LogConfig();

try{

    $config = [

        'table_storage' => [
            'table_name' => 'doctrine_migration_versions',
            'version_column_name' => 'version',
            'executed_at_column_name' => 'executed_at',
            'execution_time_column_name' => 'execution_time',
        ],

        'migrations_paths' => ['App\Migrations' => __DIR__ . '/app/Configs/Database/Migrations'],

        'all_or_nothing' => true,
        'check_database_platform' => true,
        
    ];

    $logger->appLogMsg('INFO', 'Migrations executed with success.');
    return $config;

} catch (Exception $ex) {
    $logger->appLogMsg('ERROR', 'Error when trying to execute the migrations, error type: ' . $ex->getMessage());
}
