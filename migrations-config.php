<?php

return [
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
        'version_column_name' => 'version',
        'executed_at_column_name' => 'executed_at',
        'execution_time_column_name' => 'execution_time',
    ],

    // * Folder for storage the migrations.
    'migrations_paths' => [
        'App\Migrations' => __DIR__ . '/app/Configs/Database/Migrations',
    ],

    'all_or_nothing' => true,
    'check_database_platform' => true,
];
