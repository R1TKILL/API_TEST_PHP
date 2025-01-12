<?php

require __DIR__ . '/../../app/Configs/Env/env.php';

use App\ContainersDI\DIContainers;
use App\configLogs\LogConfig;

$apiPrefix = (string) $dict_ENV['PREFIX_API'] ?: '/api';
$logger = new LogConfig();

$ctns = new DIContainers();
$ctns->setContainers();

return function ($app) use ($apiPrefix, $ctns, $logger) {

    try {

        $app->group($apiPrefix, function ($app) use ($ctns) {
            $app->get('/pessoa/items', [$ctns->getContainer()->get('PessoaController'), 'getAll']);
            $app->get('/pessoa/items/{id}', [$ctns->getContainer()->get('PessoaController'), 'getById']);
            $app->post('/pessoa/items', [$ctns->getContainer()->get('PessoaController'), 'create']);
            $app->put('/pessoa/items/{id}', [$ctns->getContainer()->get('PessoaController'), 'update']);
            $app->delete('/pessoa/items/{id}', [$ctns->getContainer()->get('PessoaController'), 'delete']);
        });
    
        // ? Add the others groups if necessary, ex: admin and others.

    } catch (Exception $ex) {
        $logger->appLogMsg('ERROR', 'Error in routes, type error: ' . $ex->getMessage());
    }
    
};
