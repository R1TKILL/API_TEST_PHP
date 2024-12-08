<?php

declare(strict_types=1);
namespace App\Services;

use App\configLogs\LogConfig;
use App\DAO\PessoaDAO;
use App\DTO\PessoaDTO;
use Exception;

class PessoaService {

    // * Atributes:
    private PessoaDAO $pessoaDAO;
    private PessoaDTO $pessoaDTO;
    private LogConfig $logger;


    // * Constructor:
    public function __construct(PessoaDAO $pessoaDAO, PessoaDTO $pessoaDTO) {
        $this->pessoaDAO = $pessoaDAO;
        $this->pessoaDTO = $pessoaDTO;
        $this->logger = new LogConfig();
    }


    // * Methods:
    public function getAll(int $page = 1, int $pageSize = 5): array | bool {

        try {

            $pessoas = $this->pessoaDAO->load($page, $pageSize);
            $pessoaObject = [];

            if(!$pessoas) {
                return [];
            }

            foreach ($pessoas as $pessoa) {
                $pessoaObject[] = [
                    "id" => $pessoa->getId(),
                    "name" => $pessoa->getName(),
                    "age" => $pessoa->getAge(),
                    "email" => $pessoa->getEmail(),
                    "cell" => $pessoa->getCell()
                ];
            }

            return $pessoaObject;

        } catch (Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaService->getAll, type error: ' . $ex->getMessage());
            return false;
        }

    }


    public function getById(int $id): array | bool | null {

        try {

            $pessoaByID = $this->pessoaDAO->loadById($id);

            if(!$pessoaByID) {
                return [];
            }
            
            $pessoaObjectByID = [
                "id" => $pessoaByID->getId(),
                "name" => $pessoaByID->getName(),
                "age" => $pessoaByID->getAge(),
                "email" => $pessoaByID->getEmail(),
                "cell" => $pessoaByID->getCell()
            ];

            return $pessoaObjectByID;

        } catch (Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaService->getById, type error: ' . $ex->getMessage());
            return false;
        }

    }


    public function create(array $data): bool {

        try {

            $this->pessoaDTO->name = $data['name'];
            $this->pessoaDTO->age = $data['age'];
            $this->pessoaDTO->email = $data['email'];
            $this->pessoaDTO->cell = $data['cell'];

            $this->pessoaDAO->save($this->pessoaDTO);
            return true;

        } catch (Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaService->create, type error: ' . $ex->getMessage());
            return false;
        }

    }


    public function update(int $id, array $data): bool {

        try {

            return $this->pessoaDAO->update($id, $data);

        } catch (Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaService->update, type error: ' . $ex->getMessage());
            return false;
        }

    }


    public function delete($id): bool {

        try {
 
            return $this->pessoaDAO->delete($id);

        } catch (Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaService->delete, type error: ' . $ex->getMessage());
            return false;
        }

    }

}
