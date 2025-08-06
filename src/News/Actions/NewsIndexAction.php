<?php

namespace Root\News\Actions;

use Core\Actions\RouterAwareAction;
use Core\Renderer\RendererInterface;
use Root\News\Table\NewTable;
use Root\News\Table\CategoryTable;
use Psr\Http\Message\ServerRequestInterface;

class NewsIndexAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var NewTable
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
        $news = $this->newTable->findPaginated(16, $params['p'] ?? 1);
        $categories = $this->categoryTable->findAll();
        return $this->renderer->render('@news/index', compact('news', 'categories'));
    }
}
