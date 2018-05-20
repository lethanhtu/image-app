<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gumlet\ImageResize;

class UserController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->getMethod()==='GET') {
            return $this->render('upload.html.twig');
        } else {
            $file = $request->files->get('file');

            $path = $this->container->getParameter('kernel.project_dir').'/public/images/';

            $extension = $file->getClientOriginalExtension();
            $newFileName = hash('md5', microtime());
            $newName = $newFileName.'.'.$extension;

            $file->move($path.'/original', $newName);

            $imageResize = new ImageResize($path.'/original/'.$newName);
            $imageResize->scale(10);
            $imageResize->save($path.'/thumbnail/'.$newFileName.'.'.$extension);

            return $this->redirectToRoute('homepage_index');
        }
    }
}
