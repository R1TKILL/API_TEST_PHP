<?php

declare(strict_types=1);
namespace Tests\Performance\LoadTests;

use App\Helpers\ServerTestManager;
use GuzzleHttp\Client;

class SimulateUsersTest extends ServerTestManager {
    
    public function testEndpointsPerformance() {

        $client = new Client();
        $endpoints = require __DIR__ . '/../../../../app/Helpers/api_endpoints.php';
    
        // * Config of multiply users:
        $users = 50; // * Number of users.
        $requestsPerUser = 10; // * Numbers of requests by users.
    
        // * Open or create the file of report.
        $logFile = '/../../../../app/Tests/Performance/Reports/load_test_report.md';
        file_put_contents($logFile, "# Load Test Report\n\n");
    
        foreach ($endpoints['endpoints'] as $endpoint) {

            $successCount = 0;
            $errorCount = 0;
            $totalTime = 0;
    
            for ($i = 0; $i < $users; $i++) {

                for ($j = 0; $j < $requestsPerUser; $j++) {

                    $start = microtime(true);
                    $response = $this->sendRequest($client, $endpoint['method'], $endpoint['url'], $endpoint['payload'] ?? []);
                    $end = microtime(true);
    
                    $totalTime += ($end - $start) * 1000; // * Time in milliseconds.
    
                    if ($response['status_code'] < 400) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                    
                }

            }
    
            // * Calculate rate of success.
            $totalRequests = $users * $requestsPerUser;
            $successRate = ($successCount / $totalRequests) * 100;
    
            // * Register in log.
            $logEntry = ( 
                "## Endpoint: {$endpoint['url']}\n"
                . "- **Http Method:** {$endpoint['method']}\n"
                . "- **Total Requests:** {$totalRequests}\n"
                . "- **Success:** {$successCount}\n"
                . "- **Errors:** {$errorCount}\n"
                . "- **Rate of Success:** {$successRate}%\n"
                . "- **Total Time:** " . number_format($totalTime, 2) . " ms\n\n"
            );
    
            file_put_contents($logFile, $logEntry, FILE_APPEND);
    
            // * Assertions.
            $this->assertGreaterThan(0, $successCount, "There should be at least one successful response for endpoint {$endpoint['url']}.");
            $this->assertLessThanOrEqual($totalRequests, $successCount, "The success rate is too low for endpoint {$endpoint['url']}.");
            $this->assertEquals(0, $errorCount, "There should be no errors for endpoint {$endpoint['url']}.");

        }
    }    


    private function sendRequest(Client $client, string $method, string $url, array $data = []): array {
        try {
            $response = $client->request($method, $url, [
                'json' => $data,
                'timeout' => 10,
            ]);

            return [
                'status_code' => $response->getStatusCode(),
                'body' => $response->getBody()->getContents(),
            ];
        } catch (\Exception $e) {
            return [
                'status_code' => $e->getCode(),
                'error' => $e->getMessage(),
            ];
        }
    }
    
}
