<?php

namespace Root\BookmarkManager;

use Core\Module;
use Core\Router;
use Core\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;

use Root\BookmarkManager\Actions\BookmarkManagerIndexAction;

class BookmarkManagerModule extends Module
{
    const DEFINITIONS = __DIR__.DIRECTORY_SEPARATOR.'config.php';
    const MIGRATIONS = __DIR__.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR.'migrations';
    const SEEDS = __DIR__ . DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR.'seeds';

    
    public function __construct(ContainerInterface $container)
    {
        $bookmarkManagerPrefix = $container->get('bookmarkManager.prefix');
        $container->get(RendererInterface::class)->addPath('bookmarkManager', __DIR__.DIRECTORY_SEPARATOR.'views');
        $router = $container->get(Router::class);

        $router->get($bookmarkManagerPrefix, BookmarkManagerIndexAction::class, 'bookmarkManager.index');
    }
}
