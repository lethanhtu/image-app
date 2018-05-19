<?php

/**
 * [namespace description]
 * @var [type]
 */
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * [UserController description]
 */
class UserController extends Controller
{
    public function upload(Request $request)
    {
        echo $this->container->getParameter('kernel.root_dir');
        if ($request->getMethod()=='GET') {
            return $this->render('user/upload.html.twig');
        } else {
            $file = $request->files->get('file');

            $folderPath = getenv('upload_path').'/username/';
            $file->move($folderPath);
            return new Response('OK');
        }
    }
}
