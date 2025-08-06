<?php

namespace Root\Admin;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class AdminTwigExt extends AbstractExtension
{
    /**
     * @var array
     */
    private $widgets;

    public function __construct(array $widgets)
    {
        $this->widgets = $widgets;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('admin_menu', [$this, 'renderMenu'], ['is_safe' => ['html']])
        ];
    }

    
    public function renderMenu(): string
    {
        return array_reduce($this->widgets, function (string $html, AdminWidgetInterface $widget) {
            return $html . $widget->renderMenu();
        }, '');
    }
}
