<?php

namespace Tests\Core;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Core\Router;

class RouterTest extends TestCase
{
    /**
     * @var Router
     */
    private $router;

    public function setUp(): void
    {
        $this->router = new Router();
    }

    public function testGetMethod()
    {
        $request = new ServerRequest('GET', '/news');
        $this->router->get('/news', function () {
            return 'NEWS PAGE';
        }, 'news');
        $route = $this->router->match($request);
        $this->assertEquals('news', $route->getName());
        $this->assertEquals('NEWS PAGE', call_user_func_array($route->getCallback(), [$request]));
    }
    public function testGetMethodIfURLDoesNotExist()
    {
        $request = new ServerRequest('GET', '/news');
        $this->router->get('/newsaze', function () {
            return 'NEWS PAGE';
        }, 'news');
        $route = $this->router->match($request);
        $this->assertEquals(null, $route);
    }

    public function testGetMethodWithParams()
    {
        $request = new ServerRequest('GET', '/news/slug-8');
        $this->router->get('/news', function () {
            return 'NEWS PAGE';
        }, 'news');
        $this->router->get('/news/[*:slug]-[i:id]', function () {
            return 'NEWS PAGE ID';
        }, 'news.show');
        $route = $this->router->match($request);
        $this->assertEquals('news.show', $route->getName());
        $this->assertEquals('NEWS PAGE ID', call_user_func_array($route->getCallback(), [$request]));
        $this->assertEquals(['slug' => 'slug', 'id' => '8'], $route->getParams());
        // Test invalid url
        $route = $this->router->match(new ServerRequest('GET', '/news/slug_8'));
        $this->assertEquals(null, $route);
    }

    public function testGenerateUri()
    {
        $this->router->get('/news', function () {
            return 'NEWS PAGE';
        }, 'news');
        $this->router->get('/news/[*:slug]-[i:id]', function () {
            return 'NEWS PAGE ID';
        }, 'news.show');
        $uri = $this->router->generateUri('news.show', ['slug' => 'article', 'id' => 8]);
        $this->assertEquals('/news/article-8', $uri);
    }

    public function testGenerateUriWithQueryParams()
    {
        $this->router->get('/news', function () {
            return 'NEWS PAGE';
        }, 'news');
        $this->router->get('/news/[*:slug]-[i:id]', function () {
            return 'NEWS PAGE ID';
        }, 'news.show');
        $uri = $this->router->generateUri(
            'news.show',
            ['slug' => 'article', 'id' => 8],
            ['p' => 2]
        );
        $this->assertEquals('/news/article-8?p=2', $uri);
    }
}
