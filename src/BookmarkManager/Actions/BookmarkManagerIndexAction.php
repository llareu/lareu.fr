<?php

namespace Root\BookmarkManager\Actions;

use Core\Actions\RouterAwareAction;
use Core\Renderer\RendererInterface;
use Root\BookmarkManager\Table\BookmarkManagerTable;
use Psr\Http\Message\ServerRequestInterface;

class BookmarkManagerIndexAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var BookmarkManagerTable
     */
    private $bookmarkManagerTable;

    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, BookmarkManagerTable $bookmarkManagerTable)
    {
        $this->renderer = $renderer;
        $this->bookmarkManagerTable = $bookmarkManagerTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        $bookmarks = $this->bookmarkManagerTable->findAll();
        return $this->renderer->render('@bookmarkManager/index', compact('bookmarks'));
    }
}
