<?php

namespace Root\News\Actions;

use Core\Actions\RouterAwareAction;
use Root\News\Table\NewTable;
use Core\Router;
use Core\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class NewsShowAction
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
     * @var Router
     */
    private $router;

    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, Router $router, NewTable $newTable)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->newTable = $newTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $slug = $request->getAttribute('slug');

        $news = $this->newTable->findWithCategory($request->getAttribute('id'));

        if ($news->slug !== $slug) {
            return $this->redirect('news.show', [
                'slug' => $news->slug,
                'id' => $news->id
            ]);
        }

        return $this->renderer->render('@news/show', [
            'new' => $news
        ]);
    }
}
