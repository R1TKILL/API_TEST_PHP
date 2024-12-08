<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ResponseTimeTest extends TestCase {

    private $client;
    private $settings;

    protected static $serverProcess;

    // * Config for start the server before of tests.
    public static function setUpBeforeClass(): void {
        self::$serverProcess = proc_open("composer run start:test", [], $pipes);
    }


    // * Config for end the server after of tests.
    public static function tearDownAfterClass(): void {
        if (self::$serverProcess) {
            proc_terminate(self::$serverProcess);
        }
    }

    
    protected function setUp(): void {

        // * Load configs of PHP file.
        $this->settings = require 'app/Helpers/api_endpoints.php';

        // * Create a cliente of Guzzle with base_url config.
        $this->client = new Client(['base_uri' => $this->settings['base_url']]);

    }


    public function testResponseTimes() {

        // * Define path report.
        $reportPath = 'app/Tests/Performance/Reports/response_time_report.md';
    
        // * Overwrite the file at the beginning and add the header.
        file_put_contents($reportPath, "# API Response Time Report\n\n");
        file_put_contents($reportPath, "## Base URL\n`{$this->settings['base_url']}`\n\n", FILE_APPEND);
        file_put_contents($reportPath, "## Results\n\n", FILE_APPEND);
        file_put_contents($reportPath, "| Endpoint | Method | Response Time | Status |\n", FILE_APPEND);
        file_put_contents($reportPath, "|----------|--------|---------------|--------|\n", FILE_APPEND);
    
        foreach ($this->settings['endpoints'] as $endpoint) {
            echo "Measuring response time for: {$endpoint['url']}\n";
    
            $start = microtime(true);
            $response = $this->sendRequest($endpoint['method'], $endpoint['url'], $endpoint['payload'] ?? []);
            $end = microtime(true);
    
            $responseTime = ($end - $start) * 1000; // * Time in milliseconds.
            $responseTimeInSeconds = $responseTime / 1000; // * Time in seconds.
            echo "Response time: {$responseTime} ms\n";
    
            // * Define the status with base in limit time.
            $status = $responseTime <= 5000 ? "✅ Success" : "❌ Exceeded Limit";
    
            // * Add result in the table.
            file_put_contents(
                $reportPath,
                "| `{$endpoint['url']}` | `{$endpoint['method']}` | {$responseTimeInSeconds}s ({$responseTime} ms) | {$status} |\n",
                FILE_APPEND
            );
    
            // * Assertion about response time.
            $this->assertLessThan(5000, $responseTime, 'Response time should be less than 5 seconds');
        }
    
        // * Add note to the end of report.
        file_put_contents(
            $reportPath,
            "\n> **Note:** Endpoints marked with `❌` exceeded the time limit of 5 seconds.\n",
            FILE_APPEND
        );
        
    }
    
    

    private function sendRequest(string $method, string $url, array $data = []): array {

        try {

            $response = $this->client->request($method, $url, [
                'json' => $data,
                'timeout' => $this->settings['timeout'] ?? 10,
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
