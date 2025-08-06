<?php

namespace Root\News\Actions;

use Core\Actions\CrudAction;
use Psr\Http\Message\ServerRequestInterface;
use Core\Renderer\RendererInterface;
use Core\Router;
use Root\News\Table\CategoryTable;
use Core\Session\FlashService;

class CategoryCrudAction extends CrudAction
{

    protected $viewPath = '@news/admin/category';
    protected $routePrefix = 'news.category.admin';

    public function __construct(RendererInterface $renderer, Router $router, CategoryTable $table, FlashService $flash)
    {
        parent::__construct($renderer, $router, $table, $flash);
    }

    protected function getParams(ServerRequestInterface $request): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['title', 'slug']);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        return parent::getValidator($request)
            ->required('title', 'slug')
            ->length('title', 1, 50)
            ->length('slug', 3, 60)
            ->unique('slug', $this->table->getTable(), $this->table->getPDO(), $request->getAttribute('id'))
            ->slug('slug');
    }
}
