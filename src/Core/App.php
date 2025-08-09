<?php
namespace Core;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

// chargement du fichier .env
class App implements RequestHandlerInterface
{
    /**
     * @var array
     */
    private $modules = [];

    /**
     * @var string
     */
    private $definition;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string[]
     */
    private $middleware;

    /**
     * @var integer
     */
    private $index = 0;


    public function __construct(string $definition)
    {
        $this->definition = $definition;
    }

    public function addModule(string $module): self
    {
        $this->modules[] = $module;
        return $this;
    }

    /**
     * Ajouter un middleware à la pile de traitement
     *
     * @param string $middleware
     * @return self
     */
    public function pipe(string $middleware): self
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if (is_null($middleware)) {
            throw new \Exception('Aucun middleware n\'a intercepté cette requête');
        } elseif (is_callable($middleware)) {
            return call_user_func_array($middleware, [$request, [$this, 'handle']]);
        } elseif ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->modules as $module) {
            $this->getContainer()->get($module);
        }
        return $this->handle($request);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if (is_null($this->container)) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions($this->definition);
            foreach ($this->modules as $module) {
                if ($module::DEFINITIONS) {
                    $builder->addDefinitions($module::DEFINITIONS);
                }
            }
            $this->container = $builder->build();
        }
        return $this->container;
    }

    private function getMiddleware():object | null
    {
        if (array_key_exists($this->index, $this->middleware)) {
            $middleware = $this->container->get($this->middleware[$this->index]);
            $this->index++;
            return $middleware;
        }
        return null;
    }
}
