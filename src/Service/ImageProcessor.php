<?php
namespace App\Service;

use Gumlet\ImageResize;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ImageProcessor
{
    private $doctrine;
    private $tokenStorage;
    private $path;

    public function __construct(Registry $doctrine, TokenStorage $tokenStorage,  $path = '')
    {
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
        $this->path = $path;
    }

    public function save($file)
    {
        $extension = $file->getClientOriginalExtension();
        $newFileName = hash('md5', microtime());
        $newName = $newFileName.'.'.$extension;
        $fileSize = $file->getSize();
        $file->move($this->path.'/original', $newName);

        $imageResize = new ImageResize($this->path.'/original/'.$newName);
        $imageResize->crop(300, 200);
        $imageResize->save($this->path.'/thumbnail/'.$newFileName.'.'.$extension);

        $user = $this->tokenStorage->getToken()->getUser();

        $image = new Image();
        $image->setTitle($file->getClientOriginalName());
        $image->setMimeType($file->getClientMimeType());
        $image->setSize($fileSize);
        $image->setCreatedDate(new \DateTime());
        $image->setExtension($extension);
        $image->setFilename($newName);
        $image->setUploadedBy($user);
        $this->doctrine->getEntityManager()->persist($image);
        $this->doctrine->getEntityManager()->flush();
    }

    public function get($imageId)
    {
          
    }
}
