<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends AbstractController
{

    #[Route('/signup', name: 'signup')]
    public function index(Request $request): Response
    {
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setActive(false);
            // $user->setCreatedAt(new \DateTime());
            $user->setCreatedAt(\DateTimeImmutable::createFromMutable(new \DateTime()));
            $user->setRoles(['ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();
            $this->addFlash(type: 'exito', message: 'Registro exitoso');
            return $this->redirectToRoute(route: 'login');
        }
        return $this->render('signup/index.html.twig', [
            'controller_name' => 'SignupController',
            'form' => $form->createView(),
        ]);
    }
}