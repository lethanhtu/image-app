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
        // $listImages = array_slice(scandir('images/thumbnail'), 2);
        // return $this->render('gallery.html.twig', [
        //   'images'=>$listImages
        // ]);
        //
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
        if (!$user) {
            throw $this->createNotFoundException('No image found for id '.$id);
        }

        $images = $user->getImages();
        foreach ($images as $image) {
            echo $image->getTitle()."\n";
        }

        return new Response();
    }
}
