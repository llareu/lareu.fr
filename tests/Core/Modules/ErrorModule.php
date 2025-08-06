<?php

namespace Tests\Core\Modules;

class ErrorModule
{
    public function __construct(\Core\Router $router)
    {
        $router->get('/demo', function () {
            return new \stdClass();
        }, 'demo');
    }
}
