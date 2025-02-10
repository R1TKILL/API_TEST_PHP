<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CacheMiddleware implements MiddlewareInterface {
    private string $cacheKey;
    private int $cacheDuration;

    public function __construct(string $cacheKey = 'cache_people_list', int $cacheDuration = 900) {
        $this->cacheKey = $cacheKey;
        $this->cacheDuration = $cacheDuration;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response {

        $_SESSION['cache'] = $_SESSION['cache'] ?? [];
        $currentTime = time();

        if (isset($_SESSION['cache'][$this->cacheKey])) {

            $cacheData = $_SESSION['cache'][$this->cacheKey];
            $cacheAge = $currentTime - $cacheData['timestamp'];

            if ($cacheAge < $this->cacheDuration) {
                $etag = $cacheData['etag'];
                $clientEtag = $request->getHeaderLine('If-None-Match');

                if ($clientEtag === $etag) {
                    return (new \Slim\Psr7\Response())
                        ->withStatus(304)
                        ->withHeader('Cache-Control', "max-age={$this->cacheDuration}")
                        ->withHeader('ETag', $etag);
                }
            }
            
        }

        $response = $handler->handle($request);
        $body = (string) $response->getBody();
        $etag = md5($body);
        $_SESSION['cache'][$this->cacheKey] = ['data' => $body, 'etag' => $etag, 'timestamp' => $currentTime];

        return $response
            ->withHeader('Cache-Control', "max-age={$this->cacheDuration}")
            ->withHeader('ETag', $etag);

    }
}
