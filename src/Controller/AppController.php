<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Image;

class AppController extends Controller
{
    public function index()
    {
        $images = $this->getDoctrine()->getRepository(Image::class)->findAll();
        $data = [];
        foreach ($images as $image) {
            $data[] = [
                'id'=>$image->getId(),
                'hashedName'=>$image->getHashedName(),
                'uploadedByName'=>$image->getUploadedBy()->getFullName()
            ];
        }
        return $this->render('gallery.html.twig', [
          'images' => $data
        ]);
    }
}
