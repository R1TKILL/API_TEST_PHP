<?php

require __DIR__ . '/../../app/Configs/Env/env.php';

use App\configLogs\LogConfig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

$logger = new LogConfig();
$CORS_Origin = (string) $dict_ENV['ORIGIN_ADDRESS'] ?: '*';

return function ($app) use ($CORS_Origin, $logger) {
    $app->add(function (Request $request, RequestHandler $handler) use ($CORS_Origin, $logger): Response {
        try {

            $cacheKey = 'cache_people_list';
            $cacheDuration = 10; // * 15 minutes.
            $currentTime = time();
            
            session_start();
            $_SESSION['cache'] = $_SESSION['cache'] ?? [];
            $cacheAge = ($currentTime - $_SESSION['cache'][$cacheKey]['timestamp']);

            if (isset($_SESSION['cache'][$cacheKey]) && $cacheAge < $cacheDuration) {

                $etag = $_SESSION['cache'][$cacheKey]['etag'];
                $clientEtag = $request->getHeaderLine('If-None-Match');

                if ($clientEtag === $etag) {
                    return (new \Slim\Psr7\Response())
                        ->withStatus(304)
                        ->withHeader('Cache-Control', "max-age={$cacheDuration}")
                        ->withHeader('ETag', $etag);
                }

            }

            $response = $handler->handle($request);
            $body = (string) $response->getBody();
            $etag = md5($body);
            $_SESSION['cache'][$cacheKey] = ['data' => $body, 'etag' => $etag, 'timestamp' => $currentTime];

            return $response
                ->withHeader('Access-Control-Allow-Origin', $CORS_Origin)
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withHeader('Cache-Control', "max-age={$cacheDuration}")
                ->withHeader('ETag', $etag);

        } catch (Exception $ex) {
            $logger->appLogMsg('ERROR', 'Error in middleware, type error: ' . $ex->getMessage());
            $response = new \Slim\Psr7\Response();
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500, 'Internal Server Error.');
        }
    });
};
