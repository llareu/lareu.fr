<?php

use Core\Middleware\CsrfMiddleware;
use Core\Router;
use Core\Router\RouterTwigExt;
use Core\Twig\{
    FlashExt,
    PagerFantaExt,
    TextExt,
    TimeExt,
    FormExt,
    AssetExt,
    CsrfExt
};
use Core\Session\{
    SessionInterface, 
    PHPSession
};
use Core\Renderer\{
    RendererInterface, 
    TwigRendererFactory
};
use Psr\Container\ContainerInterface;
use function \DI\{
    factory, 
    create, 
    get
};

// chargement du fichier .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

return [
    'db.host' => $_ENV['DB_HOST'] ?? 'localhost',
    'db.user' => $_ENV['DB_USER'] ?? 'root',
    'db.pass' => $_ENV['DB_PWD'] ?? '',
    'db.name' => $_ENV['DB_NAME'] ?? 'test',
    'db.port' => $_ENV['DB_PORT'] ?? '3306',
    'db.charset' => 'utf8',
    'views.path' => dirname(__DIR__).DIRECTORY_SEPARATOR.'views',
    'twig.ext' => [
        get(RouterTwigExt::class),
        get(PagerFantaExt::class), 
        get(TextExt::class),
        get(TimeExt::class),
        get(FlashExt::class),
        get(FormExt::class),
        get(AssetExt::class),
        get(CsrfExt::class)
    ],
    SessionInterface::class => create(PHPSession::class),
    CsrfMiddleware::class => create()->constructor(get(SessionInterface::class)),
    Router::class => create(),
    RendererInterface::class => factory(TwigRendererFactory::class),
    PDO::class => function (ContainerInterface $c)
    {
        return new PDO(
            'mysql:host='.$c->get('db.host').':'.$c->get('db.port').';dbname='.$c->get('db.name'), 
            $c->get('db.user'), 
            $c->get('db.pass'),
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
            );
    },
];