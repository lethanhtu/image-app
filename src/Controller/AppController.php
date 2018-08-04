<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Image;
use App\Entity\User;

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
        $listImages = [];
        return $this->render('gallery.html.twig', [
          'images' => $data
        ]);
    }
}
