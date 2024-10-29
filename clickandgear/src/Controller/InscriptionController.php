<?php
// src/Controller/InscriptionController.php

namespace App\Controller;

use App\Entity\Utilisateur; // L'entité de l'utilisateur
use App\Form\InscriptionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur(); // Crée une nouvelle instance d'utilisateur
        $form = $this->createForm(InscriptionFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                user:$user,
                plainPassword:$form->get(name:'plainPassword')->getData()
            );
            $user->setPassword(password: $hashedPassword);
            $roles = ['ROLE_USER'];
            $user->setRoles(roles: $roles);

            // Sauvegarde l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirige vers la page de connexion ou une autre page
            return $this->redirectToRoute('login');
        }

        return $this->render('inscription/inscription.html.twig', [
            'inscriptionForm' => $form->createView(),
        ]);
    }
}
