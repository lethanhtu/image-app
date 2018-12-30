<?php

use PHPUnit\Framework\TestCase;
use App\Twig\AppExtension;

class RoleTwigExtensionTest extends TestCase
{

    public function roleProvider()
    {
        return [
            [1, 'Admin'],
            [2, 'Normal user'],
            [3, 'N/A']
        ];
    }

    /**
     * @dataProvider roleProvider
     */
    public function testConvertRoleToText($intRole, $txtRole)
    {
        $appExtension = new AppExtension();
        $this->assertEquals($txtRole, $appExtension->convertRoleToText($intRole));
    }
}
