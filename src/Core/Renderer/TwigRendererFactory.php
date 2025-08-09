<?php

namespace Core\Renderer;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Psr\Container\ContainerInterface;
use Twig\Extension\DebugExtension;

class TwigRendererFactory
{
    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $debug = $container->get('env') !== 'production';
        $viewPath = $container->get('views.path');
        $loader = new FilesystemLoader($viewPath);
        $twig = new Environment($loader, [
            'debug' => $debug,
            'cache' => $debug ? false : 'tmp/cache/',
            'auto_reload' => $debug,
        ]);
        $twig->addExtension(new DebugExtension);
        if ($container->has('twig.ext')) {
            foreach ($container->get('twig.ext') as $ext) {
                $twig->addExtension($ext);
            }
        }
        return new TwigRenderer($loader, $twig);
    }
}
