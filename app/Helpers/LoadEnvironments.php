<?php

require __DIR__ . '/../../app/Configs/Env/env.php';

if(getenv('APP_ENV') == '' || getenv('APP_ENV') == null) {
    echo "\n***APP_ENV is empty or null!***\n";
    putenv('APP_ENV=test');
}

return $dict_ENV;

