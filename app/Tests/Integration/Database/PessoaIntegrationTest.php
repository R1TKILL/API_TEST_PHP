<?php

declare(strict_types=1);
namespace App\Tests\Integration\Database;

use App\Database\DatabaseTestConnection;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use App\Models\Pessoa;

class PessoaIntegrationTest extends TestCase {

    private DatabaseTestConnection $databaseConnection;
    private EntityManager $entityManager;

    protected function setUp(): void {

        // * Get the instance of database:
        $this->databaseConnection = new DatabaseTestConnection();
        $this->entityManager = $this->databaseConnection->getConnection();

        // * Config SchemaTool for recreate the database schema on each test:
        $schemaTool = new SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($classes); 
        $schemaTool->createSchema($classes);

    }


    public function testCreatePessoa() {

        $pessoa1 = new Pessoa();
        $pessoa1->setName('Maria Silva');
        $pessoa1->setAge(30);
        $pessoa1->setEmail('maria.silva@example.com');
        $pessoa1->setCell('55912345678');
        $this->entityManager->persist($pessoa1);

        $pessoa2 = new Pessoa();
        $pessoa2->setName('João Santos');
        $pessoa2->setAge(25);
        $pessoa2->setEmail('joao.santos@example.com');
        $pessoa2->setCell('55987654321');
        $this->entityManager->persist($pessoa2);

        $pessoa3 = new Pessoa();
        $pessoa3->setName('Ana Clara');
        $pessoa3->setAge(20);
        $pessoa3->setEmail('ana.clara@example.com');
        $pessoa3->setCell('55912312345');
        $this->entityManager->persist($pessoa3);

        $this->entityManager->flush();

        $this->assertNotNull($pessoa1->getId());
        $this->assertNotNull($pessoa2->getId());
        $this->assertNotNull($pessoa3->getId());

    }


    public function testReadAllPessoas() {

        $pessoa1 = new Pessoa();
        $pessoa1->setName('João Santos');
        $pessoa1->setAge(25);
        $pessoa1->setEmail('joao.santos@example.com');
        $pessoa1->setCell('55987654321');
        $this->entityManager->persist($pessoa1);

        $pessoa2 = new Pessoa();
        $pessoa2->setName('Maria Silva');
        $pessoa2->setAge(30);
        $pessoa2->setEmail('maria.silva@example.com');
        $pessoa2->setCell('55912345678');
        $this->entityManager->persist($pessoa2);

        $pessoa3 = new Pessoa();
        $pessoa3->setName('Ana Clara');
        $pessoa3->setAge(20);
        $pessoa3->setEmail('ana.clara@example.com');
        $pessoa3->setCell('55912312345');
        $this->entityManager->persist($pessoa3);

        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(Pessoa::class);
        $pessoas = $repository->findAll();

        $this->assertCount(3, $pessoas);
        $this->assertEquals('joao.santos@example.com', $pessoas[0]->getEmail());
        $this->assertEquals('maria.silva@example.com', $pessoas[1]->getEmail());
        $this->assertEquals('ana.clara@example.com', $pessoas[2]->getEmail());
        
    }


    public function testReadPessoaById() {

        $pessoa = new Pessoa();
        $pessoa->setName('Ana Clara');
        $pessoa->setAge(22);
        $pessoa->setEmail('ana.clara@example.com');
        $pessoa->setCell('55911223344');
        $this->entityManager->persist($pessoa);
        $this->entityManager->flush();

        $retrievedPessoa = $this->entityManager->find(Pessoa::class, $pessoa->getId());
        $this->assertNotNull($retrievedPessoa);

        $this->assertEquals('Ana Clara', $retrievedPessoa->getName());
        $this->assertEquals(22, $retrievedPessoa->getAge());
        $this->assertEquals('ana.clara@example.com', $retrievedPessoa->getEmail());
        $this->assertEquals('55911223344', $retrievedPessoa->getCell());

    }


    public function testUpdatePessoa() {

        $pessoa = new Pessoa();
        $pessoa->setName('Ana Clara');
        $pessoa->setAge(20);
        $pessoa->setEmail('ana.clara@example.com');
        $pessoa->setCell('55912312345');

        $this->entityManager->persist($pessoa);
        $this->entityManager->flush();

        $pessoa->setAge(21);
        $this->entityManager->flush();

        $updatedPessoa = $this->entityManager->find(Pessoa::class, $pessoa->getId());
        $this->assertEquals(21, $updatedPessoa->getAge());

    }


    public function testDeletePessoa() {

        $pessoa = new Pessoa();
        $pessoa->setName('Carlos Souza');
        $pessoa->setAge(40);
        $pessoa->setEmail('carlos.souza@example.com');
        $pessoa->setCell('55998765432');

        $this->entityManager->persist($pessoa);
        $this->entityManager->flush();

        // * Removing:
        $pessoaId = $pessoa->getId();
        $this->entityManager->remove($pessoa);
        $this->entityManager->flush();

        $deletedPessoa = $this->entityManager->find(Pessoa::class, $pessoaId);
        $this->assertNull($deletedPessoa);

    }
    
}
