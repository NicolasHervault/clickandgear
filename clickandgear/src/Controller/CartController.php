<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Accessory;
use App\Repository\AccessoryRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(CartRepository $cartRepository): Response
    {
        $user = $this->getUser(); // Assurez-vous que l'utilisateur est bien récupéré ici
        if (!$user) {
            return $this->redirectToRoute('login'); // Rediriger vers la connexion si l'utilisateur n'est pas connecté
        }

        $cartItems = $cartRepository->findBy(['user' => $user]);

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add_ajax', methods: ['POST'])]
    public function addAjax(Accessory $accessory, EntityManagerInterface $em, CartRepository $cartRepository): JsonResponse
    {
        $user = $this->getUser(); // Assurez-vous que l'utilisateur est bien récupéré ici

        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        $cartItem = $cartRepository->findOneBy(['user' => $user, 'accessory' => $accessory]);

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            $cartItem = new Cart();
            $cartItem->setUser($user);
            $cartItem->setAccessory($accessory);
            $cartItem->setQuantity(1);
            $em->persist($cartItem);
        }

        $em->flush();

        $cartCount = $cartRepository->getTotalItemsCount($user);

        return new JsonResponse(['cartCount' => $cartCount]);
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove', methods: ['POST'])]
    public function remove($id, CartRepository $cartRepository, EntityManagerInterface $entityManager): Response
    {
        $cartItem = $cartRepository->find($id);

        if (!$cartItem) {
            throw $this->createNotFoundException('Article introuvable dans le panier.');
        }

        $entityManager->remove($cartItem);
        $entityManager->flush();

        return $this->redirectToRoute('cart_index'); // Redirige vers la page du panier
    }
    #[Route('/cart/increase/{id}', name: 'cart_increase_quantity', methods: ['POST'])]
public function increaseQuantity(Cart $cartItem, EntityManagerInterface $em): JsonResponse
{
    $cartItem->setQuantity($cartItem->getQuantity() + 1);
    $em->flush();

    return new JsonResponse(['quantity' => $cartItem->getQuantity(), 'price' => $cartItem->getAccessory()->getPrix()]);
}

#[Route('/cart/decrease/{id}', name: 'cart_decrease_quantity', methods: ['POST'])]
public function decreaseQuantity(Cart $cartItem, EntityManagerInterface $em): JsonResponse
{
    if ($cartItem->getQuantity() > 1) {
        $cartItem->setQuantity($cartItem->getQuantity() - 1);
        $em->flush();
    } else {
        $em->remove($cartItem);
        $em->flush();
        return new JsonResponse(['quantity' => 0, 'removed' => true]);
    }

    return new JsonResponse(['quantity' => $cartItem->getQuantity(), 'price' => $cartItem->getAccessory()->getPrix()]);
}
#[Route('/cart/count', name: 'cart_count', methods: ['GET'])]
public function getCartCount(CartRepository $cartRepository): JsonResponse
{
    $user = $this->getUser();
    $cartCount = $cartRepository->getTotalItemsCount($user);

    return new JsonResponse(['cartCount' => $cartCount]);
}


}
