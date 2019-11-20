<?php

namespace App\Services\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('splitPhone', [$this, 'formatPhone'], [])
        ];
    }

    public function formatPhone($content): string
    {

        $str = $content;
        $str = wordwrap($str, 2, ".", 1);
        $phoneNumber = $str;

        return $phoneNumber;
    }
}
