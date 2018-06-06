<?php
namespace App\Service;

use Gumlet\ImageResize;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Registry;

class ImageProcessor
{
    private $doctrine;
    private $path;

    public function __construct(Registry $doctrine, $path = '')
    {
        $this->doctrine = $doctrine;
        $this->path = $path;
    }

    public function save($file)
    {
        $extension = $file->getClientOriginalExtension();
        $newFileName = hash('md5', microtime());
        $newName = $newFileName.'.'.$extension;

        $file->move($this->path.'/original', $newName);

        $imageResize = new ImageResize($this->path.'/original/'.$newName);
        $imageResize->crop(300, 200);
        $imageResize->save($this->path.'/thumbnail/'.$newFileName.'.'.$extension);

        $randomUserid = rand(22, 31);
        $user = $this->doctrine->getRepository(User::class)->find($randomUserid);

        $image = new Image();
        $image->setTitle($file->getClientOriginalName());
        $image->setMimeType($file->getClientMimeType());
        $image->setSize($file->getClientSize());
        $image->setCreatedDate(new \DateTime());
        $image->setExtension($extension);
        $image->setFilename($newName);
        $image->setUploadedBy($user);
        $this->doctrine->getEntityManager()->persist($image);
        $this->doctrine->getEntityManager()->flush();
    }
}
