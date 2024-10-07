<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORMEntityManagerInterface;
use App\Form\UserRegistrationType;
use App\Entity\User;

class UserController extends AbstractController
{
    
 #[Route('/register', name: 'user.inscription')]
    public function inscription(Request $request, EntityManagerInterface $entityManager)
    {
        $user = new User();

        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}




