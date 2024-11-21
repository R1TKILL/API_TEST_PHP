<?php

require './app/Configs/Env/env.php';

$host = (string) $dict_ENV['HOST'];
$port = (string) $dict_ENV['PORT'];

exec("php -S $host:$port app/index.php");
