<?php
// src/Repository/CartRepository.php

namespace App\Repository;
use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function getTotalItemsCount($user): int
{
    $qb = $this->createQueryBuilder('c')
               ->select('SUM(c.quantity)')
               ->where('c.user = :user')
               ->setParameter('user', $user);

    return (int) $qb->getQuery()->getSingleScalarResult();
}
}
