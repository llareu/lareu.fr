<?php

namespace Core\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return new Response(404, [], 'Error 404: Not Found.');
    }
}
