<?php

require './app/Configs/Database/Connection/connection.php';

use DI\Container;
use App\Models\Pessoa;
use App\Services\PessoaService;
use App\Controllers\PessoaController;

$container = new Container();

// * Registering PessoaModel
$container->set('Pessoa', function() {
    return new Pessoa();
});

// * Registering PessoaService, injecting PessoaModel and EntityManager.
$container->set('PessoaService', function($container) use ($entityManager) {
    return new PessoaService($container->get('Pessoa'), $entityManager);
});

// * Registering PessoaController, injecting PessoaService.
$container->set('PessoaController', function($container) {
    return new PessoaController($container->get('PessoaService'));
});
