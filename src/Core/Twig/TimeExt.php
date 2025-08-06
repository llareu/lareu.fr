<?php
namespace Core\Twig;

use Twig\Extension\AbstractExtension;
use \Twig\TwigFilter;

/**
 *
 * Serie ext concernant les text
 *
 */

class TimeExt extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('ago', [$this, 'ago'], ['is_safe' => ['html']])
        ];
    }

    public function ago(\DateTime $date, string $format = 'd/m/Y H:i')
    {
        return (
            '<span class="timeago" datetime="'.$date->format(\DateTime::ATOM).'">'.
                $date->format($format).
            '</span>'
        );
    }
}
