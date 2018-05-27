<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
