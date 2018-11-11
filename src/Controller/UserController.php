<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\UserUploadEvent;
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

    public function upload(Request $request, ImageProcessor $imageProcessor, EventDispatcherInterface $dispatcher)
    {
        if ($request->getMethod()==='GET') {
            return $this->render('upload.html.twig');
        } else {
            $event = new UserUploadEvent($request->files->get('image'));
            $dispatcher->dispatch(UserUploadEvent::NAME, $event);
            return $this->redirectToRoute('index');
        }
    }

    public function like(Request $request)
    {
        $imageId = $request->get('image_id');

        $doctrine = $this->getDoctrine();

        $image = $doctrine->getRepository(Image::class)->find($imageId);

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $manager = $doctrine->getManager();

        if($image->getLikedBy()->contains($user)) {
            $image->removeLikedBy($user);
            $liked = false;
        } else {
            $image->addLikedBy($user);
            $liked = true;
        }

        $manager->persist($image);
        $manager->flush();

        $template = $this->get('twig')->loadTemplate('image_detail.html.twig');

        return new JsonResponse([
            'success' => true,
            'data' => $template->renderBlock('like', [
                'liked' => $liked,
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

        return $this->render('image_detail.html.twig', [
            'likeCount' => count($likedBy),
            'liked'=> $likedBy->contains($user),
            'image'=>$image
        ]);
    }

    public function download($imageId)
    {
        $image = $this->getDoctrine()->getRepository(Image::class)->find($imageId);

        if($image) {
            throw new \Exception('Image not found');
        }

        $content = file_get_contents($this->container->getParameter('kernel.project_dir').'/public/images/original/'.$image->getHashedName());
        return new Response($content, Response::HTTP_OK, [
            'Content-Type' => 'image/jpeg',
            'Content-Length' => 424586,
            'Content-Disposition'=> sprintf('attachment; filename="%s"', $image->getFilename())
        ]);
    }

    public function delete($imageId)
    {
        $image = $images = $this->getDoctrine()->getRepository(Image::class)->find($imageId);
        if (empty($image)) {
            return new JsonResponse([
                'success' => false,
                'data'=> 'Image not found'
            ], 404);
        }

        $this->denyAccessUnlessGranted('delete', $image);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($image);
        $manager->flush();

        return new JsonResponse([
            'success'=>true
        ], 200);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profile()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('profile.html.twig',[
            'username'=>$user->getUsername(),
            'fullName'=>$user->getFullName(),
            'email'=>$user->getEmail(),
            'createdDate'=>$user->getCreatedDate(),
            'role'=>$user->getRole(),
            'totalUpload'=>$this->getDoctrine()->getRepository(Image::class)->getTotalByUser($user->getId())
        ]);
    }
}
