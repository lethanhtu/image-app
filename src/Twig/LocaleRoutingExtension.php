<?php

namespace App\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LocaleRoutingExtension  extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getUrlWithLocale', array($this, 'getUrlWithLocale'))
        ];
    }

    public function getUrlWithLocale($requestAttribute, $locale)
    {
        $params = $requestAttribute->get('_route_params');
    }
}
