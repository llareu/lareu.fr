<?php

namespace Root\News;

use Core\Renderer\RendererInterface;
use Root\Admin\AdminWidgetInterface;
use Root\News\Table\NewTable;

class NewsWidget implements AdminWidgetInterface
{
    /*
    * @var RendererInterface
    */
    private $renderer;
    private $newTable;

    public function __construct(RendererInterface $renderer, NewTable $newTable)
    {
        $this->renderer = $renderer;
        $this->newTable = $newTable;
    }

    public function render(): string
    {
        $count = $this->newTable->count();
        return $this->renderer->render('@news/admin/widget', Compact('count'));
    }

    public function renderMenu(): string
    {
        return $this->renderer->render('@news/admin/menu');
    }
}
