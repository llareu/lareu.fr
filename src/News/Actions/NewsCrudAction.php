<?php

namespace Root\News\Actions;

use Core\Actions\CrudAction;
use Psr\Http\Message\ServerRequestInterface;
use Core\Renderer\RendererInterface;
use Core\Router;
use Root\News\Table\NewTable;
use Root\News\Table\CategoryTable;
use Core\Session\FlashService;
use Root\News\Entity\NewsEntity;

class NewsCrudAction extends CrudAction
{

    protected $viewPath = '@news/admin/news';

    protected $routePrefix = 'news.admin';

    /**
     * @var categoryTable
     */
    private $categoryTable;

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        NewTable $table,
        CategoryTable $categoryTable,
        FlashService $flash
    ) {
        parent::__construct($renderer, $router, $table, $flash);
        $this->categoryTable = $categoryTable;
    }

    
    protected function formParams(array $params): array
    {
        $params['categories'] = $this->categoryTable->findList();
        $params['categories']['123456789']= 'Catego inco';
        return $params;
    }

    protected function getNewEntity()
    {
        $news = new NewsEntity();
        $news->created_at = new \DateTime();
        return $news;
    }

    protected function getParams(ServerRequestInterface $request): array
    {
        $params = array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['title', 'content', 'slug', 'created_at', 'category_id']);
        }, ARRAY_FILTER_USE_KEY);
        return array_merge($params, [
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        return parent::getValidator($request)
            ->required('title', 'content', 'slug', 'created_at', 'category_id')
            ->length('title', 1, 50)
            ->length('content', 1)
            ->length('slug', 3, 60)
            ->exists('category_id', $this->categoryTable->getTable(), $this->categoryTable->getPDO())
            ->dateTime('created_at')
            ->slug('slug');
    }
}
