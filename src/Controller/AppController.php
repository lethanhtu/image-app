<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
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
                'like'=>count($image->getLikedBy()),
                'comment'=>count($image->getComments()),
                'fileName'=>$image->getFileName(),
                'uploadedByName'=>$image->getUploadedBy()->getFullName()
            ];
        }
        $listImages = [];
        return $this->render('gallery.html.twig', [
          'images' => $data
        ]);
    }

    public function view($id)
    {
        $image = $images = $this->getDoctrine()->getRepository(Image::class)->find($id);
        if(!$image) {
            return new Response('Image not found');
        }

        return $this->render('image_detail.html.twig',[
            'image'=>[
                'fileName'=>$image->getFileName()
            ]
        ]);
    }
}
