<?php
require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

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

$modules = [
    AdminModule::class,
    NewsModule::class,
    BookmarkManagerModule::class,
];


$app = (new App(dirname(__DIR__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php'))
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
