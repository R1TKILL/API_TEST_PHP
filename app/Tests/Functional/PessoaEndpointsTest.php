<?php

declare(strict_types=1);

use App\Database\DatabaseTestConnection;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;
use App\Helpers\ServerTestManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PessoaEndpointsTest extends ServerTestManager {

    private string $baseUrl; // Agora vamos usar diretamente a baseUrl do arquivo api_endpoints.php
    private DatabaseTestConnection $databaseConnection;
    private EntityManager $entityManager;
    private Client $httpClient;
    private array $endpoints;

    // * Config the params for url.
    protected function setUp(): void {

        // * Get the instance of database:
        $this->databaseConnection = new DatabaseTestConnection();
        $this->entityManager = $this->databaseConnection->getConnection();

        // * Config SchemaTool for recreate the database schema on each test:
        $schemaTool = new SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($classes); 
        $schemaTool->createSchema($classes);

        // * Load endpoints configuration from api_endpoints.php
        $this->endpoints = require 'app/Helpers/api_endpoints.php';

        // * Directly use the baseUrl from api_endpoints.php
        $this->baseUrl = $this->endpoints['base_url']; // A baseUrl já está configurada no arquivo api_endpoints.php

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

    // * Test all endpoints dynamically from the api_endpoints configuration.
    public function testEndpoints() {
        foreach ($this->endpoints['endpoints'] as $endpoint) {

            $url = $this->baseUrl . $endpoint['url']; // Agora, usamos a baseUrl já configurada
            $method = $endpoint['method'];
            $payload = $endpoint['payload'] ?? null;

            // Skip testing DELETE if no payload is provided (DELETE does not require a payload)
            if ($method !== 'DELETE' && !$payload) {
                continue;
            }

            // Execute the request
            $response = $this->makeRequest($method, $url, $payload ? json_encode($payload) : null);

            // Check HTTP status codes based on method type
            if ($method === 'POST') {
                $this->assertEquals(201, $response['http_code'], "POST request failed for {$endpoint['url']}");
            } elseif ($method === 'GET') {
                $this->assertEquals(200, $response['http_code'], "GET request failed for {$endpoint['url']}");
            } elseif ($method === 'PUT') {
                $this->assertEquals(200, $response['http_code'], "PUT request failed for {$endpoint['url']}");
            } elseif ($method === 'DELETE') {
                $this->assertEquals(200, $response['http_code'], "DELETE request failed for {$endpoint['url']}");
            }

            // Further assertions can be added here based on expected response content
            if ($method === 'GET') {
                $data = json_decode($response['body'], true);
                $this->assertIsArray($data);
                $this->assertNotEmpty($data);
            }

            // Optionally, you can log each endpoint's response time, status, and more
        }
    }
}
