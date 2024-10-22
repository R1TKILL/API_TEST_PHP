<?php

require './app/Configs/Env/env.php';
require 'app/Configs/Containers/containers.php';

$apiPrefix = (string) $dict_ENV['PREFIX_API'] ?: '/api';

return function ($app) use ($apiPrefix, $container) {
    
    $app->group($apiPrefix, function ($app) use ($container) {

        $app->get('/pessoa/items', [$container->get('PessoaController'), 'getAll']);
        $app->get('/pessoa/items/{id}', [$container->get('PessoaController'), 'getById']);
        $app->post('/pessoa/items', [$container->get('PessoaController'), 'create']);
        $app->put('/pessoa/items/{id}', [$container->get('PessoaController'), 'update']);
        $app->delete('/pessoa/items/{id}', [$container->get('PessoaController'), 'delete']);

    });

    // ? Add the others groups if necessary, ex: admin and others.

};
