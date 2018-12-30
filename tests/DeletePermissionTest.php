<?php

use PHPUnit\Framework\TestCase;
use App\Twig\AppExtension;
use App\Entity\User;
use App\Entity\Image;
use App\Security\ImageVoter;

class DeletePermissionTest extends TestCase
{

    public function roleProvider()
    {
        return [
            [1, 'Admin'],
            [2, 'Normal user'],
            [3, 'N/A']
        ];
    }

    public function testDeletePermissionUploadedByCurrentUser()
    {
        $mockImage = $this->createMock(Image::class);
        $mockUser = $this->createMock(User::class);
        $voter = new ImageVoter();


        $mockImage->expects($this->once())->method('getUploadedBy')->willReturn($mockUser);
        $mockUser->expects($this->never())->method('getRole')->willReturn(User::ROLE_ADMIN);
        $this->assertTrue($voter->canDelete($mockImage, $mockUser));
    }

    public function testDeletePermissionAccountAdmin()
    {
        $mockImage = $this->createMock(Image::class);
        $mockAdminAccount = $this->createMock(User::class);
        $user = new User();
        $voter = new ImageVoter();


        $mockImage->expects($this->once())->method('getUploadedBy')->willReturn($user);
        $mockAdminAccount->expects($this->once())->method('getRole')->willReturn(User::ROLE_ADMIN);
        $this->assertTrue($voter->canDelete($mockImage, $mockAdminAccount));
    }

    public function testDeletePermissionNotOwnedNotAdmin()
    {
        $mockImage = $this->createMock(Image::class);
        $mockUser = $this->createMock(User::class);
        $user = new User();
        $voter = new ImageVoter();


        $mockImage->expects($this->once())->method('getUploadedBy')->willReturn($user);
        $mockUser->expects($this->once())->method('getRole')->willReturn(User::ROLE_USER);
        $this->assertFalse($voter->canDelete($mockImage, $mockUser));
    }

}
