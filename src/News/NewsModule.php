<?php

namespace Root\News;

use Core\Module;
use Core\Router;
use Core\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;

use Root\News\Actions\NewsShowAction;
use Root\News\Actions\NewsIndexAction;
use Root\News\Actions\CategoryShowAction;

use Root\News\Actions\CategoryCrudAction;
use Root\News\Actions\NewsCrudAction;

class NewsModule extends Module
{
    const DEFINITIONS = __DIR__.DIRECTORY_SEPARATOR.'config.php';
    const MIGRATIONS = __DIR__.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR.'migrations';
    const SEEDS = __DIR__ . DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR.'seeds';

    
    public function __construct(ContainerInterface $container)
    {
        $newsPrefix = $container->get('news.prefix');
        $container->get(RendererInterface::class)->addPath('news', __DIR__.DIRECTORY_SEPARATOR.'views');
        $router = $container->get(Router::class);

        $router->get($newsPrefix, NewsIndexAction::class, 'news.index');
        $router->get($newsPrefix.'/show/[*:slug]/[*:id]', NewsShowAction::class, 'news.show');
        $router->get($newsPrefix.'/category/show/[*:slug]', CategoryShowAction::class, 'news.category');


        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->crud("$prefix/news", NewsCrudAction::class, 'news.admin');
            $router->crud("$prefix/category", CategoryCrudAction::class, 'news.category.admin');
        }
    }
}
