<?php

require './app/Configs/Env/env.php';

use App\ContainersDI\Containers;

$ctns = new Containers();
$ctns->setContainers();
$apiPrefix = (string) $dict_ENV['PREFIX_API'] ?: '/api';

return function ($app) use ($apiPrefix, $ctns) {
    
    $app->group($apiPrefix, function ($app) use ($ctns) {
        $app->get('/pessoa/items', [$ctns->getContainer()->get('PessoaController'), 'getAll']);
        $app->get('/pessoa/items/{id}', [$ctns->getContainer()->get('PessoaController'), 'getById']);
        $app->post('/pessoa/items', [$ctns->getContainer()->get('PessoaController'), 'create']);
        $app->put('/pessoa/items/{id}', [$ctns->getContainer()->get('PessoaController'), 'update']);
        $app->delete('/pessoa/items/{id}', [$ctns->getContainer()->get('PessoaController'), 'delete']);
    });

    // ? Add the others groups if necessary, ex: admin and others.

};
