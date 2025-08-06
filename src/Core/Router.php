<?php

namespace Core;

use AltoRouter;
use Core\Router\Route;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Router
 * Register and match routes
 */
class Router
{
    /**
     * @var AltoRouter;
     */

    private $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }

    /**
     * @param string $path
     * @param callable|string $callback
     * @param string $name
     */
    public function get(string $path, callable|string $callback, ?string $name = null)
    {
        $this->router->map("GET", $path, $callback, $name);
    }

        /**
     * @param string $path
     * @param callable|string $callback
     * @param string $name
     */
    public function post(string $path, callable|string $callback, ?string $name = null)
    {
        $this->router->map("POST", $path, $callback, $name);
    }

    /**
     * @param string $path
     * @param callable|string $callback
     * @param string $name
     */
    public function delete(string $path, callable|string $callback, ?string $name = null)
    {
        $this->router->map("DELETE", $path, $callback, $name);
    }


    public function crud(string $prefixPath, $callable, string $prefixName)
    {
        $this->get("$prefixPath", $callable, "$prefixName.index");
        $this->get("$prefixPath/add", $callable, "$prefixName.create");
        $this->post("$prefixPath/add", $callable);
        $this->get("$prefixPath/[*:id]", $callable, "$prefixName.edit");
        $this->post("$prefixPath/[*:id]", $callable);
        $this->delete("$prefixPath/[*:id]", $callable, "$prefixName.delete");
    }

    /**
     * @param ServerRequestinterface $request
     * @return Route/null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request->getUri()->getPath());
        if ($result) {
            return new Route($result['target'], $result['params'], $result['name']);
        }
        return null;
    }

    public function generateUri(string $name, array $params = [], array $QueryParams = []): ?string
    {
        $uri = $this->router->generate($name, $params);
        if (!empty($QueryParams)) {
            return $uri . '?' . http_build_query($QueryParams);
        }
        return $uri;
    }
}
