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
        $listImages = array_slice(scandir('images/thumbnail'), 2);
        return $this->render('gallery.html.twig', [
          'images'=>$listImages
        ]);
    }
}
