<?php

require './app/Configs/Env/env.php';

return [
    'driver'        => (string) $dict_ENV['DB_DIALECT'],
    'user'          => (string) $dict_ENV['DB_USER'],
    'password'      => (string) $dict_ENV['DB_PASS'],
    'dbname'        => (string) $dict_ENV['DB_NAME'],
    'host'          => (string) $dict_ENV['DB_HOST'],   
    'port'          => (string) $dict_ENV['DB_PORT'],              
    'sslmode'       => (string) $dict_ENV['DB_SSLMODE'],
    'sslrootcert'   => (string) $dict_ENV['DB_SSLROOTCERT'],
    'charset'       => (string) $dict_ENV['DB_CHARSET'],
    'driverOptions' => [PDO::ATTR_TIMEOUT => 5]
];
