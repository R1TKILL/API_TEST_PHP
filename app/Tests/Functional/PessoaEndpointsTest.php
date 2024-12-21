<?php

declare(strict_types=1);

use App\Database\DatabaseTestConnection;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;
use App\Helpers\ServerTestManager;

// ! Funciona perfeitamente, já no github_actions em testPostEndpoint não encontra a dependency_injection na rota.
class PessoaEndpointsTest extends ServerTestManager {

    private array $env;
    private string $host;
    private string $port;
    private string $api_prefix;
    private string $baseUrl;
    private DatabaseTestConnection $databaseConnection;
    private EntityManager $entityManager;

    // * Config the params for url.
    protected function setUp(): void {

        // * Load environments.
        $this->env = require 'app/Helpers/LoadEnvironments.php';

        // * Get the instance of database:
        $this->databaseConnection = new DatabaseTestConnection();
        $this->entityManager = $this->databaseConnection->getConnection();

        // * Config SchemaTool for recreate the database schema on each test:
        $schemaTool = new SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($classes); 
        $schemaTool->createSchema($classes);

        // * Config address for tests.
        $this->host = (string) $this->env['HOST'];
        $this->port = (string) $this->env['PORT'];
        $this->api_prefix = (string) $this->env['PREFIX_API'];
        $this->baseUrl = "http://{$this->host}:{$this->port}{$this->api_prefix}";

    }


    // * Testing a POST endpoint.
    public function testPostEndpoint() {

        $url = $this->baseUrl . '/pessoa/items';
        $payload = json_encode([
            'name' => 'Alfredo Aguiar de Macedo',
            'age' => 35,
            'email' => 'alfredo_aguiar193@gmail.com',
            'cell' => '5521978114588'
        ]);

        // * Executing the request.
        $response = $this->makeRequest('POST', $url, $payload);

        // * Verify if the HTTP status is 201 (Created).
        $this->assertEquals(201, $response['http_code']);

    }


    // * Testing a GET endpoint.
    public function testGetEndpoint() {

        $this->testPostEndpoint();

        $url = $this->baseUrl . '/pessoa/items';

        // * Executing the request.
        $response = $this->makeRequest('GET', $url);

        // * Verify if the HTTP status is 200 (OK).
        $this->assertEquals(200, $response['http_code']);

        // * Verify if the return contains expects dates forms.
        $data = json_decode($response['body'], true);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

    }


    // * Testing a PUT endpoint.
    public function testPutEndpoint() {

        $this->testPostEndpoint();

        $url = $this->baseUrl . '/pessoa/items/1';
        $payload = json_encode([
            'name' => 'Alfredo Aguiar de Macedo',
            'email' => 'alfredo_aguiar349@gmail.com',
            'age' => 38,
            'cell' => '5521978114588'
        ]);

        // * Executing the request.
        $response = $this->makeRequest('PUT', $url, $payload);

        // * Verify if the HTTP status is 200 (OK).
        $this->assertEquals(200, $response['http_code']);

        // * Verify if the dates have been updated.
        if ($response['http_code'] == 200) {

            $response = $this->makeRequest('GET', $url);
            $data = json_decode($response['body'], true);

            $this->assertEquals('alfredo_aguiar349@gmail.com', $data['email']);
            $this->assertEquals(38, $data['age']);

        }

    }


    // * Testing a DELETE endpoint.
    public function testDeleteEndpoint() {

        $this->testPostEndpoint();

        $url = $this->baseUrl . '/pessoa/items/1';

        // * Executing the request.
        $response = $this->makeRequest('DELETE', $url);

        // * Verify if the HTTP status is 200 (OK).
        $this->assertEquals(200, $response['http_code']);

    }


    // * Method for do making HTTP requests.
    private function makeRequest(string $method, string $url, string $payload = null): array {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($payload) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            ]);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        echo "\nMetodo => $method\n";
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        echo "\n";

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'http_code' => $httpCode,
            'body' => $response,
        ];

    }

}
