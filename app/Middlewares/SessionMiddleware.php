<?php

declare(strict_types=1);
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionMiddleware {

    public function __construct(Request $request, RequestHandler $handler) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $handler->handle($request);

    }

}
