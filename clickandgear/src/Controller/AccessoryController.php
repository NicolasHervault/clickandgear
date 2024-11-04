<?php

namespace App\Controller;

use App\Repository\AccessoryRepository;
use App\Entity\Accessory;
use App\Form\AccessoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AccessoryController extends AbstractController
{
    #[Route('/admin/accessory/new', name: 'accessory_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accessory = new Accessory();

        // Créer le formulaire
        $form = $this->createForm(AccessoryType::class, $accessory);

        // Gérer la requête de soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Déplacer l'image dans le répertoire des uploads
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gestion des erreurs d'upload
                }

                // Assigner le nom de fichier à l'entité Accessory
                $accessory->setImage($newFilename);
            }

            // Persister l'entité dans la base de données
            $entityManager->persist($accessory);
            $entityManager->flush();

            // Rediriger vers la page des accessoires après ajout
            return $this->redirectToRoute('accessory_list');
        }

        // Rendre le template du formulaire
        return $this->render('accessory/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Nouvelle action pour afficher la liste des accessoires
    #[Route('/home', name: 'accessory_list')]
    public function home(Request $request, AccessoryRepository $accessoryRepository): Response
    {
        // Récupérer les filtres depuis la requête
        $categorie = $request->query->get('categorie');
        $prixMin = $request->query->get('prix_min');
        $prixMax = $request->query->get('prix_max');

        // Convertir les prix en float
        $prixMin = $prixMin !== null ? (float) $prixMin : null;
        $prixMax = $prixMax !== null ? (float) $prixMax : null;

        // Utiliser le repository pour appliquer les filtres
        $accessories = $accessoryRepository->findByFilters($categorie, $prixMin, $prixMax);

        return $this->render('accessory/home.html.twig', [
            'accessories' => $accessories,
        ]);
    }

    #[Route('/admin/accessory/{id}/edit', name: 'accessory_edit')]
    public function edit(Request $request, Accessory $accessory, EntityManagerInterface $entityManager): Response
    {
        // Créer le formulaire et le remplir avec les données actuelles de l'accessoire
        $form = $this->createForm(AccessoryType::class, $accessory);

        // Gérer la requête de soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de la nouvelle image (si modifiée)
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Déplacer l'image dans le répertoire des uploads
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gestion des erreurs d'upload
                }

                // Mettre à jour l'image dans l'entité
                $accessory->setImage($newFilename);
            }

            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger vers la page de liste des accessoires
            return $this->redirectToRoute('accessory_list');
        }

        // Rendre la vue pour l'édition
        return $this->render('accessory/edit.html.twig', [
            'form' => $form->createView(),
            'accessory' => $accessory,
        ]);
    }

    #[Route('/admin/accessory/{id}/delete', name: 'accessory_delete', methods: ['POST'])]
    public function delete(Request $request, Accessory $accessory, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si le jeton CSRF est valide pour éviter les suppressions non intentionnelles
        if ($this->isCsrfTokenValid('delete'.$accessory->getId(), $request->request->get('_token'))) {
            // Supprimer l'accessoire
            $entityManager->remove($accessory);
            $entityManager->flush();
        }

        // Rediriger vers la liste après la suppression
        return $this->redirectToRoute('accessory_list');
    }
}
