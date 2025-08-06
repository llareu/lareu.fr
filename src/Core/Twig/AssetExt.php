<?php

namespace Core\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class AssetExt extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', [$this, 'asset'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Génère le chemin d'accès à un asset en partant du rep. racine du projet.
     * @param string $path Chemin de l'asset
     * @return string Chemin complet de l'asset
     */
    public function asset(string $path): string
    {
        return '../../../' . ltrim($path);
    }
}
