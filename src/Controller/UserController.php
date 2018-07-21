<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\ImageProcessor;
use App\Form\RegisterType;
use App\Entity\User;
use App\Entity\Image;

class UserController extends Controller
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash(
            'notice',
            'Thank you for your registration, you can login <a href="'.$this->generateUrl('login').'"> here </a>'
        );
            return $this->redirectToRoute('app_index');
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

    public function like(Request $request)
    {
        $imageId = $request->get('image_id');
        $action = $request->get('action');
        $doctrine = $this->getDoctrine();

        $image = $images = $doctrine->getRepository(Image::class)->find($imageId);

        // Validate image id is invalid
        if(empty($image)) {
            return new JsonResponse([
                'success' => false,
                'error_info'=>'image_not_found'
            ]);
        }

        // Validate action is invalid
        if($action !=='unlike' &&  $action !== 'like') {
            return new JsonResponse([
                'success' => false,
                'error_info'=>'action_invalid'
            ]);
        }

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        // Check there is a logged in user
        if('object' !== gettype($user)) {
            return new JsonResponse([
                'success'=>false,
                'error_info' => 'required_login'
            ]);
        }

        $manager = $doctrine->getManager();

        if($action == 'like') {
            $image->addLikedBy($user);
            $likedByCurrentUser  = true;
        } else {
            $image->removeLikedBy($user);
            $likedByCurrentUser = false;
        }

        $manager->persist($image);
        $manager->flush();

        $twig = $this->get('twig');
        $template = $twig->loadTemplate('image_detail.html.twig');

        return new JsonResponse([
            'success' => true,
            'html' => $template->renderBlock('like',[
                'id' => $imageId,
                'likedByCurrentUser' => $likedByCurrentUser,
                'likeCount' => count($image->getLikedBy())
            ])
        ]);
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
