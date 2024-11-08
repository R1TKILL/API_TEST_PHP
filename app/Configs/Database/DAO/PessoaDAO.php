<?php

declare(strict_types=1);
namespace App\DAO;

use Doctrine\DBAL\Exception as DBALException;
use App\IRepository\IPessoaRepository;
use Doctrine\ORM\EntityManager;
use App\Models\Pessoa;
use App\DTO\PessoaDTO;
use Exception;

class PessoaDAO implements IPessoaRepository {

    // * Atributes:
    private EntityManager $entityManager;
    private Pessoa $pessoaModel;


    // * Constructor:
    public function __construct(Pessoa $pessoaModel, EntityManager $entityManager) {
        $this->pessoaModel = $pessoaModel;
        $this->entityManager = $entityManager;
    }

    // * Methods:
    public function load(): array | bool {

        try {

            $pessoasData = $this->entityManager->getRepository($this->pessoaModel::class)->findAll();

            if(is_null($pessoasData)) {
                return false;
            }

            return $pessoasData;

        } catch(Exception $e) {
            echo('Error in PessoaDAO->load, type error: ' . $e->getMessage());
            return false;
        } catch(DBALException $de) {
            $this->entityManager->rollback();
            echo('Database error in PessoaDAO->load, type error: ' . $de->getMessage());
            return false;
        }

    }


    public function loadById(int $id): Pessoa | bool {

        try {

            $pessoaDataByID = $this->entityManager->find($this->pessoaModel::class, $id);

            if(is_null($pessoaDataByID)) {
                return false;
            }

            return $pessoaDataByID;

        } catch(Exception $e) {
            echo('Error in PessoaDAO->loadById, type error: ' . $e->getMessage());
            return false;
        } catch(DBALException $de) {
            $this->entityManager->rollback();
            echo('Database error in PessoaDAO->loadById, type error: ' . $de->getMessage());
            return false;
        }

    }


    public function save(PessoaDTO $pessoaDTO) {

        try {

            $this->pessoaModel->setName($pessoaDTO->name);
            $this->pessoaModel->setAge($pessoaDTO->age);
            $this->pessoaModel->setEmail($pessoaDTO->email);
            $this->pessoaModel->setCell($pessoaDTO->cell);

            print_r($pessoaDTO);
            $this->entityManager->beginTransaction();
            $this->entityManager->persist($this->pessoaModel);
            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch(Exception $e) {
            echo('Error in PessoaDAO->save, type error: ' . $e->getMessage());
        } catch(DBALException $de) {
            $this->entityManager->rollback();
            echo('Database error in PessoaDAO->save, type error: ' . $de->getMessage());
        }

    }


    public function update(int $id, array $data): bool {

        try {

            $pessoa = $this->entityManager->find($this->pessoaModel::class, $id);

            if ($pessoa == null) {
                return false;
            }

            $pessoa->setName($data['name']);
            $pessoa->setAge($data['age']);
            $pessoa->setEmail($data['email']);
            $pessoa->setCell($data['cell']);
        
            $this->entityManager->beginTransaction();
            $this->entityManager->flush();
            $this->entityManager->commit();
            return true;

        } catch(Exception $e) {
            echo('Error in PessoaDAO->update, type error: ' . $e->getMessage());
            return false;
        } catch(DBALException $de) {
            $this->entityManager->rollback();
            echo('Database error in PessoaDAO->update, type error: ' . $de->getMessage());
            return false;
        }

    }


    public function delete($id): bool {

        try {

            $pessoa = $this->entityManager->find($this->pessoaModel::class, $id);

            if ($pessoa == null) {
                return false;
            }

            $this->entityManager->beginTransaction();
            $this->entityManager->remove($pessoa);
            $this->entityManager->flush();
            $this->entityManager->commit();
            return true;

        } catch(Exception $e) {
            echo('Error in PessoaDAO->delete, type error: ' . $e->getMessage());
            return false;
        } catch(DBALException $de) {
            $this->entityManager->rollback();
            echo('Database error in PessoaDAO->delete, type error: ' . $de->getMessage());
            return false;
        }

    }

}

