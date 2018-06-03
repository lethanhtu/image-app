<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Comment;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $om)
    {
        for($i = 1; $i <= 10; $i++){
            $user = new User();
            $user->setUsername('user_'.$i);
            $user->setEmail('email_'.$i.'@local.com');
            $user->setFullname('fullname_'.$i);
            $user->setPassword('123456');
            $om->persist($user);
            $numOfImage = rand(1,6);
            for($j =1 ; $j <= $numOfImage; $j ++){
                $image = new Image();
                $image->setTitle('title_'.$j);
                $image->setSize(rand(40,100));
                $image->setMimeType('image/jpg');
                $image->setUploadedBy($user);
                $om->persist($image);
                $numOfComment = rand(1,7);
                for($k =1 ; $k <= $numOfComment; $k ++){
                    $comment  = new Comment();
                    $comment->setContent('content_'.$i);
                    $comment->setImage($image);
                    $comment->setUser($user);
                    $om->persist($comment);
                }
            }
        }
        $om->flush();
    }
}
