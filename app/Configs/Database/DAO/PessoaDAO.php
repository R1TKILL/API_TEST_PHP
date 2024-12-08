<?php

declare(strict_types=1);
namespace App\DAO;

use Doctrine\DBAL\Exception as DBALException;
use App\IRepository\IEntityRepository;
use Doctrine\ORM\EntityManager;
use App\configLogs\LogConfig;
use App\Models\Pessoa;
use App\DTO\PessoaDTO;
use Exception;

class PessoaDAO implements IEntityRepository {

    // * Atributes:
    private EntityManager $entityManager;
    private Pessoa $pessoaModel;
    private LogConfig $logger;


    // * Constructor:
    public function __construct(Pessoa $pessoaModel, EntityManager $entityManager) {
        $this->pessoaModel = $pessoaModel;
        $this->entityManager = $entityManager;
        $this->logger = new LogConfig();
    }

    // * Methods:
    public function load(int $page = 1, int $pageSize = 5): array | bool {

        try {

            // * Calculate the offset for multiply searches in database.
            $offset = (($page - 1) * $pageSize);

            // * For specific search in database.
            $queryBuilder = $this->entityManager->createQueryBuilder();
            $queryBuilder->select('p')->from($this->pessoaModel::class, 'p')
                         ->setFirstResult($offset) // * This is where the search begins.
                         ->setMaxResults($pageSize); // * Limit of search.
            
            // * Execute the query an get the results.
            $this->entityManager->beginTransaction(); // * in case of rollback.
            $pessoasData = $queryBuilder->getQuery()->getResult();


            // ! This is one a search without pagination, in case of search 100.000 dates return all 😔 .
            // $pessoasData = $this->entityManager->getRepository($this->pessoaModel::class)->findAll();

            if(is_null($pessoasData)) {
                return false;
            }

            return $pessoasData;

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaDAO->load, type error: ' . $ex->getMessage());
            return false;
        } catch(DBALException $de) {
            $this->logger->appLogMsg('ERROR', 'Database error in PessoaDAO->load, type error: ' . $de->getMessage());
            $this->entityManager->rollback();
            return false;
        }

    }


    public function loadById(int $id): Pessoa | bool {

        try {

            $this->entityManager->beginTransaction();
            $pessoaDataByID = $this->entityManager->find($this->pessoaModel::class, $id);

            if(is_null($pessoaDataByID)) {
                return false;
            }

            return $pessoaDataByID;

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaDAO->loadById, type error: ' . $ex->getMessage());
            return false;
        } catch(DBALException $de) {
            $this->logger->appLogMsg('ERROR', 'Database error in PessoaDAO->loadById, type error: ' . $de->getMessage());
            $this->entityManager->rollback();
            return false;
        }

    }


    public function save(object $pessoaDTO) {

        if (!$pessoaDTO instanceof PessoaDTO) {
            throw new \InvalidArgumentException("Expected an instance of PessoaDTO.");
        }

        try {

            $this->pessoaModel->setName($pessoaDTO->name);
            $this->pessoaModel->setAge($pessoaDTO->age);
            $this->pessoaModel->setEmail($pessoaDTO->email);
            $this->pessoaModel->setCell($pessoaDTO->cell);

            $this->entityManager->beginTransaction();
            $this->entityManager->persist($this->pessoaModel);
            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaDAO->save, type error: ' . $ex->getMessage());
        } catch(DBALException $de) {
            $this->entityManager->rollback();
            $this->logger->appLogMsg('ERROR', 'Database error in PessoaDAO->save, type error: ' . $de->getMessage());
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

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaDAO->update, type error: ' . $ex->getMessage());
            return false;
        } catch(DBALException $de) {
            $this->entityManager->rollback();
            $this->logger->appLogMsg('ERROR', 'Database error in PessoaDAO->update, type error: ' . $de->getMessage());
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

        } catch(Exception $ex) {
            $this->logger->appLogMsg('ERROR', 'Error in PessoaDAO->delete, type error: ' . $ex->getMessage());
            return false;
        } catch(DBALException $de) {
            $this->entityManager->rollback();
            $this->logger->appLogMsg('ERROR', 'Database error in PessoaDAO->delete, type error: ' . $de->getMessage());
            return false;
        }

    }

}

