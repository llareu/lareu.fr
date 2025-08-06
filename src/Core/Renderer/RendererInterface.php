<?php

namespace Core\Renderer;

interface RendererInterface
{

    /**
     * Can add the path to load the views
     * @param string $namespace
     * @param null|string $path
     */
    public function addPath(string $namespace, ?string $path = null): void;

    /**
     * Can send to server a view
     * the patch must be precise with namespace and addPath()
     * $this->render('@namespace/view');
     * $this->render('view');
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string;

    /**
     * Can add a global string in all views
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void;
}
