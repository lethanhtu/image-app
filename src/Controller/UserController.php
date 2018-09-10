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
            return $this->redirectToRoute('index');
        }

        return $this->render('register.html.twig', [
            'form'=> $form->createView()
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

    public function upload(Request $request, ImageProcessor $imageProcessor)
    {
        if ($request->getMethod()==='GET') {
            return $this->render('upload.html.twig');
        } else {
            $imageProcessor->save($request->files->get('image'));
            return $this->redirectToRoute('index');
        }
    }

    public function like(Request $request)
    {
        $imageId = $request->get('image_id');
        $action = $request->get('action');
        $doctrine = $this->getDoctrine();

        $image = $images = $doctrine->getRepository(Image::class)->find($imageId);

        // Validate image id is invalid
        if (empty($image)) {
            return new JsonResponse([
                'success' => false,
                'error_info'=>'image_not_found'
            ]);
        }

        // Validate action is invalid
        if ($action !=='unlike' &&  $action !== 'like') {
            return new JsonResponse([
                'success' => false,
                'error_info'=>'action_invalid'
            ]);
        }

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $manager = $doctrine->getManager();

        if ($action == 'like') {
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
            'html' => $template->renderBlock('like', [
                'id' => $imageId,
                'likedByCurrentUser' => $likedByCurrentUser,
                'likeCount' => count($image->getLikedBy())
            ])
        ]);
    }

    public function view($imageId)
    {
        $image = $images = $this->getDoctrine()->getRepository(Image::class)->find($imageId);
        if (!$image) {
            return new Response('Image not found');
        }

        $likedBy = $image->getLikedBy();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $likedByCurrentUser = false;

        if ('object' == gettype($user) && $likedBy->contains($user)) {
            $likedByCurrentUser = true;
        }
        return $this->render('image_detail.html.twig', [
            'hashedName'=>$image->getHashedName(),
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
        $image = $this->getDoctrine()->getRepository(Image::class)->find($imageId);
        if ($image) {
            $content = file_get_contents($this->container->getParameter('kernel.project_dir').'/public/images/original/'.$image->getHashedName());
            return new Response($content, Response::HTTP_OK, [
                'Content-Type' => 'image/jpeg',
                'Content-Length' => 424586,
                'Content-Disposition'=> sprintf('attachment; filename="%s"', $image->getFilename())
            ]);
        }

        throw new \Exception('Image not found');
    }

    public function delete($imageId)
    {
        $image = $images = $this->getDoctrine()->getRepository(Image::class)->find($imageId);
        if ($image) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            if ($user->getId() != $image->getUploadedBy()->getId() && $user->getRole() != User::ROLE_ADMIN) {
                throw new \Exception('Something wrong');
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->remove($image);
            $manager->flush();
            return new Response('Delete successfully');
        }

        throw new \Exception('Image not found');
    }

    public function profile()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('profile.html.twig',[
            'username'=>$user->getUsername(),
            'fullName'=>$user->getFullName(),
            'email'=>$user->getEmail(),
            'createdDate'=>$user->getCreatedDate(),
            'role'=>$user->getRole(),
            'totalUpload'=>$this->getDoctrine()->getRepository(Image::class)->getNumberOfUploadedFileByUser($user->getId()),
        ]);
    }

    public function changeLanguage()
    {

    }
}
