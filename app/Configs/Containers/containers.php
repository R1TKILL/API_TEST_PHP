<?php

declare(strict_types=1);

namespace App\ContainersDI;

use DI\Container;
use App\Models\Pessoa;
use App\Services\PessoaService;
use App\Controllers\PessoaController;
use App\DAO\PessoaDAO;
use App\DTO\PessoaDTO;
use App\Database\DatabaseConnection;

class Containers {

    private Container $container;

    public function __construct() {
        $this->container = new Container();
    }


    public function setContainers(): void {
        
        // * Registering PessoaModel
        $this->container->set('Pessoa', function() {
            return new Pessoa();
        });


        // * Registering DatabaseConnection
        $this->container->set('DatabaseConnection', function() {
            $dc = new DatabaseConnection();
            return $dc->getConnection();
        });


        // * Registering PessoaDAO, injecting PessoaModel and EntityManager.
        $this->container->set('PessoaDAO', function($container) {
            return new PessoaDAO($container->get('Pessoa'), $container->get('DatabaseConnection'));
        });


        // * Registering PessoaDTO.
        $this->container->set('PessoaDTO', function() {
            return new PessoaDTO();
        });


        // * Registering PessoaService, injecting PessoaDAO and PessoaDTO.
        $this->container->set('PessoaService', function($container) {
            return new PessoaService($container->get('PessoaDAO'), $container->get('PessoaDTO'));
        });


        // * Registering PessoaController, injecting PessoaService.
        $this->container->set('PessoaController', function($container) {
            return new PessoaController($container->get('PessoaService'));
        });

    }


    public function getContainer(): Container {
        return $this->container;
    }

}
