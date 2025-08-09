<?php

use Core\App;
use Root\News\NewsModule;
use Root\Admin\AdminModule;
use Root\BookmarkManager\BookmarkManagerModule;
use GuzzleHttp\Psr7\ServerRequest;
use Core\Middleware\ {
    MethodMiddleware, 
    TrailingSlashMiddleware, 
    RouterMiddleware, 
    DispatcherMiddleware,
    NotFoundMiddleware, CsrfMiddleware
};
use Middlewares\Whoops;

chdir(dirname(__DIR__));

require 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$modules = [
    AdminModule::class,
    NewsModule::class,
    BookmarkManagerModule::class,
];

// chargement du fichier .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = (new App('config'.DIRECTORY_SEPARATOR.'config.php'))
    ->addModule(NewsModule::class)
    ->addModule(AdminModule::class)
    ->addModule(BookmarkManagerModule::class)
    ->pipe(Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class);


if (php_sapi_name() !== "cli")
{
    $response = $app->run(ServerRequest::fromGlobals());
    Http\Response\send($response);
}
