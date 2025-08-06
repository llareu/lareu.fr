<?php
namespace Root\Admin;

use Core\Module;
use Core\Renderer\RendererInterface;
use Core\Renderer\TwigRenderer;
use Core\Router;

class AdminModule extends Module
{
    const DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR.'config.php';

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        AdminTwigExt $AdminTwigExt,
        string $prefix
    ) {
        $renderer->addPath('admin', __DIR__ . DIRECTORY_SEPARATOR.'views');
        $router->get($prefix, DashboardAction::class, 'admin');
        if ($renderer instanceof TwigRenderer) {
            $renderer->getTwig()->addExtension($AdminTwigExt);
        }
    }
}
