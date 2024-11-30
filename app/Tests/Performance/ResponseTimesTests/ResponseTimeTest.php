<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ResponseTimeTest extends TestCase {

    private $client;
    private $settings;

    // * Config for start the server before of tests.
    protected static $serverProcess;

    public static function setUpBeforeClass(): void {
        self::$serverProcess = proc_open("php -r \"putenv('APP_ENV=test'); include 'start.php';\"", [], $pipes);
    }


    // * Config for end the server after of tests.
    public static function tearDownAfterClass(): void {
        if (self::$serverProcess) {
            proc_terminate(self::$serverProcess);
        }
    }

    
    protected function setUp(): void {

        // * Load configs of JSON file.
        $this->settings = json_decode(file_get_contents('app/Tests/Performance/Helpers/api_endpoints.json'), true);

        // * Create a cliente of Guzzle with base_url config.
        $this->client = new Client(['base_uri' => $this->settings['base_url']]);

    }


    public function testResponseTimes() {

        foreach ($this->settings['endpoints'] as $endpoint) {

            echo "Measuring response time for: {$endpoint['url']}\n";

            $start = microtime(true);
            $response = $this->sendRequest($endpoint['method'], $endpoint['url'], $endpoint['payload'] ?? []);
            $end = microtime(true);

            $responseTime = ($end - $start) * 1000; // * Time in milliseconds.
            echo "Response time: {$responseTime} ms\n";

            // * Save the times in report.
            file_put_contents(
                'app/Tests/Performance/Reports/response_time_report.md', 
                "{$endpoint['url']}: {$responseTime} ms\n", 
                FILE_APPEND
            );

            // * Assertions about response time.
            $this->assertLessThan(5000, $responseTime, 'Response time should be less than 2 seconds');

        }

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
