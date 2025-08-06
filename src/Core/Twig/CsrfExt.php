<?php

namespace Core\Twig;

use Twig\TwigFunction;
use Core\Middleware\CsrfMiddleware;
use Twig\Extension\AbstractExtension;

class CsrfExt extends AbstractExtension
{
    private $csrfMiddleware;

    public function __construct(CsrfMiddleware $csrfMiddleware)
    {
        $this->csrfMiddleware = $csrfMiddleware;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('csrf_input', [$this, 'csrfInput'], ['is_safe' => ['html']]),
        ];
    }

    public function csrfInput()
    {
        return '<input type="hidden" 
            name = "' . $this->csrfMiddleware->getFormKey() . '" 
            value = "' . $this->csrfMiddleware->generateToken() . '"/>
        ';
    }
}
