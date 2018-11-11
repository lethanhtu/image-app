<?php
namespace App\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Gumlet\ImageResize;
use App\Entity\Image;
use App\Entity\User;

class ImageProcessor
{
    private $entityManager;
    private $tokenStorage;
    private $path;

    public function __construct(EntityManager $entityManager, TokenStorage $tokenStorage, $path = '')
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->path = $path;
    }

    public function save($file)
    {
        $extension = $file->getClientOriginalExtension();
        $hashedName = hash('md5', microtime()).'.'.$extension;
        $fileSize = $file->getSize();
        $file->move($this->path.'/original', $hashedName);

        $imageResize = new ImageResize($this->path.'/original/'.$hashedName);


        $imageResize->crop(300, 200);
        $imageResize->save($this->path.'/thumbnail/'.$hashedName);

        $imageResize->resizeToWidth(900);
        $imageResize->save($this->path.'/detail/'.$hashedName);

        $user = $this->tokenStorage->getToken()->getUser();

        $image = new Image();
        $image->setFilename($file->getClientOriginalName());
        $image->setMimeType($file->getClientMimeType());
        $image->setSize($fileSize);
        $image->setCreatedDate(new \DateTime());
        $image->setHashedName($hashedName);
        $image->setUploadedBy($user);
        $this->entityManager->persist($image);
        $this->entityManager->flush();
    }
}
