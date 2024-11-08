<?php

declare(strict_types=1);
namespace App\Services;

use App\DAO\PessoaDAO;
use App\DTO\PessoaDTO;
use Exception;

class PessoaService {

    // * Atributes:
    private PessoaDAO $pessoaDAO;
    private PessoaDTO $pessoaDTO;


    // * Constructor:
    public function __construct(PessoaDAO $pessoaDAO, PessoaDTO $pessoaDTO) {
        $this->pessoaDAO = $pessoaDAO;
        $this->pessoaDTO = $pessoaDTO;
    }


    // * Methods:
    public function getAll(): array | bool {

        try {

            $pessoas = $this->pessoaDAO->load();
            $pessoaObject = [];

            if(!$pessoas) {
                return false;
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

        } catch (Exception $e) {
            echo('Error in PessoaService->getAll, type error: ' . $e->getMessage());
            return false;
        }

    }


    public function getById(int $id): array | bool | null {

        try {

            $pessoaByID = $this->pessoaDAO->loadById($id);

            if(!$pessoaByID) {
                return false;
            }
            
            $pessoaObjectByID = [
                "id" => $pessoaByID->getId(),
                "name" => $pessoaByID->getName(),
                "age" => $pessoaByID->getAge(),
                "email" => $pessoaByID->getEmail(),
                "cell" => $pessoaByID->getCell()
            ];

            return $pessoaObjectByID;

        } catch (Exception $e) {
            echo('Error in PessoaService->getById, type error: ' . $e->getMessage());
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

        } catch (Exception $e) {
            echo('Error in PessoaService->create, type error: ' . $e->getMessage());
            return false;
        }

    }


    public function update(int $id, array $data): bool {

        try {

            return $this->pessoaDAO->update($id, $data);

        } catch (Exception $e) {
            echo('Error in PessoaService->update, type error: ' . $e->getMessage());
            return false;
        }

    }


    public function delete($id): bool {

        try {
            
            return $this->pessoaDAO->delete($id);

        } catch (Exception $e) {
            echo('Error in PessoaService->delete, type error: ' . $e->getMessage());
            return false;
        }

    }

}
