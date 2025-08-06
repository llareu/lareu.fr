<?php

namespace Root\News\Actions;

use Psr\Http\Message\ServerRequestInterface;
use Core\Renderer\RendererInterface;
use Root\News\Table\CategoryTable;
use Core\Actions\RouterAwareAction;
use Root\News\Table\NewTable;

class CategoryShowAction
{
        /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var NewTable;
    */
    private $newTable;

    /**
     * @var CategoryTable
     */
    private $categoryTable;

    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, NewTable $newTable, CategoryTable $categoryTable)
    {
        $this->renderer = $renderer;
        $this->newTable = $newTable;
        $this->categoryTable = $categoryTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        $category = $this->categoryTable->findBy('slug', $request->getAttribute('slug'));
        $news = $this->newTable->findPaginatedPublicForCategory(16, $params['p'] ?? 1, $category->id);
        $categories = $this->categoryTable->findAll();
        $page = $params['p'] ?? 1;

        return $this->renderer->render('@news/index', compact('news', 'categories', 'category', 'page'));
    }
}
