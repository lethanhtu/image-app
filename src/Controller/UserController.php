<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\ImageProcessor;
use App\Form\UserType;
use App\Entity\User;
use App\Entity\Image;

class UserController extends Controller
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirect('localhost:8000');
        }

        return $this->render('register.html.twig', [
            'form'=> $form->createView()
        ]);
    }


    public function upload(Request $request, ImageProcessor $imageProcessor)
    {
        if ($request->getMethod()==='GET') {
            return $this->render('upload.html.twig');
        } else {
            $imageProcessor->save($request->files->get('image'));
            return $this->redirectToRoute('app_index');
        }
    }

    public function login(AuthenticationUtils $authUtil)
    {
        $error = $authUtil->getLastAuthenticationError();

        $lastUsername = $authUtil->getLastUsername();

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error'=>$error
        ]);
    }

    public function logout()
    {
        return new Response();
    }
}
