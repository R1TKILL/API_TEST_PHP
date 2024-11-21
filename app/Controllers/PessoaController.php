<?php

declare(strict_types=1);
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\PessoaService; // ? pode ser mudado por um PessoaServiceRepository - classe, não interface.
use App\configLogs\LogConfig;
use Exception;

class PessoaController {

    // * Atributes:
    private PessoaService $pessoaService;
    private LogConfig $logger;


    // * Constructor:
    public function __construct(PessoaService $pessoaService) {
        $this->pessoaService = $pessoaService;
        $this->logger = new LogConfig();
    }


    // * Methods:
    public function getAll(Request $request, Response $response): Response {

        try {

            $queryParams = $request->getQueryParams();
            $page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;
            $pageSize = isset($queryParams['pageSize']) ? (int)$queryParams['pageSize'] : 5;

            $pessoas = $this->pessoaService->getAll($page, $pageSize);
    
            if(!$pessoas or $pessoas == null) {
                return $response->withStatus(404, 'Error when trying to list to dates of database');
            }
    
            $response->getBody()->write(json_encode($pessoas));
            $response->withHeader('Content-Type', 'application/json');
            return $response;

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->getAll, type error: ' . $ex->getMessage());
        }
        
    }


    public function getById(Request $request, Response $response, $args): Response {

        try {

            $id = (int) $args['id'];
            $pessoa = $this->pessoaService->getById($id);

            if(!$pessoa or $pessoa == null) {
                return $response->withStatus(404, "People not found");
            }

            $response->getBody()->write(json_encode($pessoa));
            return $response->withHeader('Content-Type', 'application/json');

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->getById, type error: ' . $ex->getMessage());
        }

    }


    public function create(Request $request, Response $response): Response {

        try {

            $data = $request->getParsedBody();
            $result = $this->pessoaService->create($data);

            if($result) {
                $response->withHeader('Content-Type', 'text/plain')->withStatus(201, 'Created successfully');
            } else {
                $response->withHeader('Content-Type', 'text/plain')->withStatus(500, 'Error when trying to insert the dates');
            }

            return $response;

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->create, type error: ' . $ex->getMessage());
        }

    }


    public function update(Request $request, Response $response, $args): Response {

        try {

            $id = (int) $args['id'];
            $data = $request->getParsedBody();
            $updatedPessoa = $this->pessoaService->update($id, $data);

            if($updatedPessoa) {
                $response->withHeader('Content-Type', 'text/plain')->withStatus(200, 'Updated successfully');
                return $response;
            } else {
                $response->withHeader('Content-Type', 'text/plain')->withStatus(500, 'Error when trying to update dates');
                return $response;
            }

            return $response;

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->update, type error: ' . $ex->getMessage());
        }

    }

    
    public function delete(Request $request, Response $response, $args): Response {

        try {

            $id = $args['id'];
            $deleted = $this->pessoaService->delete($id);

            if(!$deleted) {
                return $response->withStatus(500, 'Error when trying to remove to date of database');
            }

            return $response->withStatus(200, 'Removed successfully');

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->delete, type error: ' . $ex->getMessage());
        }

    }
}
