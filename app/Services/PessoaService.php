<?php

declare(strict_types=1);
namespace App\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception as DBALException;
use App\Models\Pessoa;
use Exception;

class PessoaService
{

    // * Atributes:
    private EntityManager $entityManager;
    private Pessoa $pessoaModel;


    // * Constructor:
    public function __construct(Pessoa $pessoaModel, EntityManager $entityManager) {
        $this->pessoaModel = $pessoaModel;
        $this->entityManager = $entityManager;
    }


    // * Methods:
    public function getAll(): array | bool | null {
        try {

            $pessoas = $this->entityManager->getRepository($this->pessoaModel::class)->findAll();
            $pessoaObject = [];

            foreach ($pessoas as $this->pessoaModel) {
                $pessoaObject[] = [
                    "id" => $this->pessoaModel->getId(),
                    "name" => $this->pessoaModel->getName(),
                    "age" => $this->pessoaModel->getAge(),
                    "email" => $this->pessoaModel->getEmail(),
                    "cell" => $this->pessoaModel->getCell()
                ];
            }

            return $pessoaObject;

        } catch (DBALException $e) {
            echo('ORM error: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            echo('general error: ' . $e->getMessage());
            return false;
        }
    }


    public function getById(int $id): array | bool | null {
        try {

            $pessoaByID = $this->entityManager->find($this->pessoaModel::class, $id); 
            
            $pessoaObjectByID = [
                "id" => $pessoaByID->getId(),
                "name" => $pessoaByID->getName(),
                "age" => $pessoaByID->getAge(),
                "email" => $pessoaByID->getEmail(),
                "cell" => $pessoaByID->getCell()
            ];

            return $pessoaObjectByID;

        } catch (DBALException $e) {
            echo('ORM error: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            echo('general error: ' . $e->getMessage());
            return false;
        }
    }


    public function create(array $data): bool {
        try {
            $this->pessoaModel->setName($data['name']);
            $this->pessoaModel->setAge($data['age']);
            $this->pessoaModel->setEmail($data['email']);
            $this->pessoaModel->setCell($data['cell']);

            $this->entityManager->persist($this->pessoaModel);
            $this->entityManager->flush();
            return true;
        } catch (DBALException $e) {
            echo('ORM error: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            echo('general error: ' . $e->getMessage());
            return false;
        }
    }


    public function update(int $id, array $data): bool {
        try {
            $pessoa = $this->entityManager->getRepository($this->pessoaModel::class)->find($id);

            if (!$pessoa or $pessoa == null) {
                return false;
            }

            $pessoa->setName($data['name']);
            $pessoa->setAge($data['age']);
            $pessoa->setEmail($data['email']);
            $pessoa->setCell($data['cell']);
            
            $this->entityManager->flush();
            return true;
        } catch (DBALException $e) {
            echo('ORM error: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            echo('general error: ' . $e->getMessage());
            return false;
        }
    }


    public function delete($id) {
        try {
            $pessoa = $this->entityManager->getRepository($this->pessoaModel::class)->find($id);

            if(!$pessoa or $pessoa == null) {
                return false;
            }

            $this->entityManager->remove($pessoa);
            $this->entityManager->flush();
            return true;
        } catch (DBALException $e) {
            echo('ORM error: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            echo('general error: ' . $e->getMessage());
            return false;
        }
    }
}
