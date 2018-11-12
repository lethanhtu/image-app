<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\User;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('role_text', [$this, 'convertRoleToText']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getRoleText', [$this, 'convertRoleToText']),
        ];
    }

    public function convertRoleToText($role)
    {
        if($role == User::ROLE_ADMIN) {
            return 'Admin';
        }

        return 'Normal user';
    }
}
