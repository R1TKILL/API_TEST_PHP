<?php

declare(strict_types=1);

namespace App\ContainersDI;

use DI\Container;
use App\DTO\PessoaDTO;
use App\Models\Pessoa;
use App\DAO\PessoaDAO;
use App\configLogs\LogConfig;
use App\Services\PessoaService;
use App\Database\DatabaseConnection;
use App\Controllers\PessoaController;
use Exception;

class DIContainers {

    private Container $container;
    private LogConfig $logger;

    public function __construct() {
        $this->container = new Container();
        $this->logger = new LogConfig();
    }


    public function setContainers(): void {

        try {

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

        } catch (Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in ContainerDI, error type: ' . $ex->getMessage());
        }
    
    }


    public function getContainer(): Container {
        return $this->container;
    }

}
