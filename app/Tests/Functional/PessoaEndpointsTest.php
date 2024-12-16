<?php

declare(strict_types=1);

use App\Database\DatabaseTestConnection;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;
use App\Helpers\ServerTestManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PessoaEndpointsTest extends ServerTestManager {

    private array $env;
    private string $host;
    private string $port;
    private string $api_prefix;
    private string $baseUrl;
    private DatabaseTestConnection $databaseConnection;
    private EntityManager $entityManager;
    private Client $httpClient;

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

        // * Initialize Guzzle Client
        $this->httpClient = new Client();
    }

    // * Method for making HTTP requests using Guzzle
    private function makeRequest(string $method, string $url, string $payload = null): array {
        try {
            $options = [];
    
            // Adiciona o payload como JSON, se fornecido
            if ($payload) {
                $options['json'] = json_decode($payload, true); // Envia o payload como JSON
            }
    
            // Executa a requisição
            $response = $this->httpClient->request($method, $url, $options);
    
            // Retorna os detalhes da resposta
            return [
                'http_code' => $response->getStatusCode(),
                'body' => (string) $response->getBody(),
            ];
        } catch (RequestException $e) {
            // Lida com erros e retorna o código e corpo da resposta
            return [
                'http_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : 500,
                'body' => $e->getMessage(),
            ];
        }
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

        // * Verify if the return contains expected data forms.
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

        // * Verify if the data has been updated.
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
}
