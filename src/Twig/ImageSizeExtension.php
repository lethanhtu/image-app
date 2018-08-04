<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ImageSizeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [new TwigFilter('size_format', array($this, 'formatSize'))];
    }

    public function formatSize($size)
    {
        $units = ['B','KB','MB','GB'];
        $index = 0;
        do {
            $size = round($size/1024);
            $index++;
        } while($size>=1024);
        return sprintf('%d %s',$size,$units[$index]);
    }
}
