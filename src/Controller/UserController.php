<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ImageProcessor;
use App\Entity\User;
use App\Entity\Image;
/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @param ImageProcessor $imageProcessor
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function upload(Request $request, ImageProcessor $imageProcessor)
    {
        if ($request->getMethod()==='GET') {
            return $this->render('upload.html.twig');
        } else {
            $imageProcessor->save($request->files->get('image'));
            return $this->redirectToRoute('app_index');
        }
    }

    public function getListLike()
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
        $imageLikes = $user->getImageLike();
        foreach($imageLikes as $image){
            echo $image->getId().'-'.$image->getTitle()."<br/>";
        }
        return new Response('ok');
    }

    public function removeLike()
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
        $image = $this->getDoctrine()->getManager()->getRepository(Image::class)->find(7);
        $user->removeImageLike($image);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new Response('ok');
    }

    public function addLike()
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
        $image = $this->getDoctrine()->getManager()->getRepository(Image::class)->find(7);
        $user->addImageLike($image);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new Response('ok');
    }
}
