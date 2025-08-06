<?php

namespace Core\Router;

use Core\Router;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class RouterTwigExt extends AbstractExtension
{
    /**
     * @var Router
     */
    private $router;
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this,'pathFor']),
            new TwigFunction('is_subpath', [$this,'isSubPath']),
        ];
    }

    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }

    public function isSubPath(string $path): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $expectedUri = $this->router->generateUri($path);
        return strpos($uri, $expectedUri) !== false;
    }
}
