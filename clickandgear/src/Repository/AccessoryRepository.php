<?php

namespace App\Repository;

use App\Entity\Accessory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Accessory>
 */
class AccessoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accessory::class);
    }

    /**
     * Trouver des accessoires par filtres
     */
    public function findByFilters(?string $categorie, ?float $prixMin, ?float $prixMax)
    {
        $qb = $this->createQueryBuilder('a');

        if ($categorie) {
            $qb->andWhere('a.categorie = :categorie')
               ->setParameter('categorie', $categorie);
        }

        if ($prixMin !== null) {
            $qb->andWhere('a.prix >= :prixMin')
               ->setParameter('prixMin', $prixMin);
        }

        if ($prixMax !== null) {
            $qb->andWhere('a.prix <= :prixMax')
               ->setParameter('prixMax', $prixMax);
        }

        return $qb->getQuery()->getResult();
    }
}