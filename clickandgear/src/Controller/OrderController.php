<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/create', name: 'order_create')]
    public function create(CartRepository $cartRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $cartItems = $cartRepository->findBy(['user' => $user]);

        $order = new Order();
        $order->setUser($user);
        $order->setCreatedAt(new \DateTime());
        $order->setStatus('en cours');

        foreach ($cartItems as $item) {
            $order->addItem($item);
            $entityManager->remove($item); // Supprime l'item du panier après création de la commande
        }

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('order_index'); // Redirige vers une page de confirmation de commande
    }
}
