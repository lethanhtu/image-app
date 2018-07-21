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
                'fileName'=>$image->getFileName(),
                'uploadedByName'=>$image->getUploadedBy()->getFullName()
            ];
        }
        $listImages = [];
        return $this->render('gallery.html.twig', [
          'images' => $data
        ]);
    }

    public function view($imageId)
    {
        $image = $images = $this->getDoctrine()->getRepository(Image::class)->find($imageId);
        if(!$image) {
            return new Response('Image not found');
        }

        $likedBy = $image->getLikedBy();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $likedByCurrentUser = false;

        if('object' == gettype($user) && $likedBy->contains($user)) {
            $likedByCurrentUser = true;
        }
        return $this->render('image_detail.html.twig',[
            'fileName'=>$image->getFileName(),
            'id'=> $image->getId(),
            'uploadedBy' => $image->getUploadedBy()->getUsername(),
            'createdDate' => $image->getCreatedDate(),
            'likeCount' => count($likedBy),
            'size' => $image->getSize(),
            'likedByCurrentUser'=> $likedByCurrentUser
        ]);
    }

    public function download($imageId)
    {
        $image = $images = $this->getDoctrine()->getRepository(Image::class)->find($imageId);
        $content = file_get_contents($this->container->getParameter('kernel.project_dir').'/public/images/original/'.$image->getFilename());
        return new Response($content, Response::HTTP_OK,[
            'Content-Type' => 'image/jpeg',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition'=> 'attachment; filename="abc.jpg"'
        ]);
    }
}
