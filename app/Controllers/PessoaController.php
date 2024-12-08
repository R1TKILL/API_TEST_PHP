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
    private string $msg;
    private LogConfig $logger;
    private int $status_code;


    // * Constructor:
    public function __construct(PessoaService $pessoaService) {
        $this->pessoaService = $pessoaService;
        $this->logger = new LogConfig();
    }


    // * Methods:
    public function getAll(Request $request, Response $response): Response {

        try {

            $queryParams = $request->getQueryParams();
            $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;
            $pageSize = isset($queryParams['pageSize']) ? (int) $queryParams['pageSize'] : 5;

            $pessoas = $this->pessoaService->getAll($page, $pageSize);

            $response->getBody()->write(json_encode($pessoas));
            ($pessoas) 
             ? [$this->status_code = 200, $this->msg = 'OK'] 
             : [$this->status_code = 404, $this->msg = 'People not found'];

            return $response->withHeader('Content-Type', 'application/json')->withStatus($this->status_code, $this->msg);

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->getAll, type error: ' . $ex->getMessage());
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500, 'Error when trying to list to dates of database');
        }
        
    }


    public function getById(Request $request, Response $response, $args): Response {

        try {

            $id = (int) $args['id'];
            $pessoa = $this->pessoaService->getById($id);

            $response->getBody()->write(json_encode($pessoa));
            ($pessoa) 
             ? [$this->status_code = 200, $this->msg = 'OK'] 
             : [$this->status_code = 404, $this->msg = 'People not found'];

            return $response->withHeader('Content-Type', 'application/json')->withStatus($this->status_code, $this->msg);


        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->getById, type error: ' . $ex->getMessage());
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500, 'Error when trying to list to data by ID of database');
        }

    }


    public function create(Request $request, Response $response): Response {

        try {

            $data = $request->getParsedBody();
            $result = $this->pessoaService->create($data);

            ($result) 
             ? [$this->status_code = 201, $this->msg = 'Created successfully'] 
             : [$this->status_code = 500, $this->msg = 'Error when trying to insert dates'];

            return $response->withHeader('Content-Type', 'application/json')->withStatus($this->status_code, $this->msg);

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->create, type error: ' . $ex->getMessage());
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500, 'Error when trying to insert the dates');
        }

    }


    public function update(Request $request, Response $response, $args): Response {

        try {

            $id = (int) $args['id'];
            $data = $request->getParsedBody();
            $updatedPessoa = $this->pessoaService->update($id, $data);

            ($updatedPessoa) 
             ? [$this->status_code = 200, $this->msg = 'Updated successfully'] 
             : [$this->status_code = 500, $this->msg = 'Error when trying to update dates'];

            return $response->withHeader('Content-Type', 'application/json')->withStatus($this->status_code, $this->msg);

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->update, type error: ' . $ex->getMessage());
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500, 'Error when trying to update dates');
        }

    }

    
    public function delete(Request $request, Response $response, array $args): Response {

        try {

            $id = (int) $args['id'];
            $deleted = $this->pessoaService->delete($id);
            
            ($deleted) 
             ? [$this->status_code = 200, $this->msg = 'Removed successfully'] 
             : [$this->status_code = 500, $this->msg = 'Error when trying to remove dates'];

            return $response->withHeader('Content-Type', 'application/json')->withStatus($this->status_code, $this->msg);

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaController->delete, type error: ' . $ex->getMessage());
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500, 'Error when trying to remove to date of database');

        }

    }
}
