<?php

declare(strict_types=1);
require './app/Configs/Database/Connection/connection.php';

use DI\Container;
use App\Models\Pessoa;
use App\Services\PessoaService;
use App\Controllers\PessoaController;
use App\DAO\PessoaDAO;
use App\DTO\PessoaDTO;

$container = new Container();

// * Registering PessoaModel
$container->set('Pessoa', function() {
    return new Pessoa();
});


// * Registering PessoaDAO, injecting PessoaModel and EntityManager.
$container->set('PessoaDAO', function($container) use ($entityManager) {
    return new PessoaDAO($container->get('Pessoa'), $entityManager);
});

// * Registering PessoaDTO.
$container->set('PessoaDTO', function() {
    return new PessoaDTO();
});


// * Registering PessoaService, injecting PessoaDAO and PessoaDTO.
$container->set('PessoaService', function($container) {
    return new PessoaService($container->get('PessoaDAO'), $container->get('PessoaDTO'));
});


// * Registering PessoaController, injecting PessoaService.
$container->set('PessoaController', function($container) {
    return new PessoaController($container->get('PessoaService'));
});
