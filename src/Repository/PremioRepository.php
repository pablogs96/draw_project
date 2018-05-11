<?php

namespace App\Repository;

use App\Entity\Premio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Premio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Premio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Premio[]    findAll()
 * @method Premio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PremioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Premio::class);
    }

//    /**
//     * @return Premio[] Returns an array of Premio objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Premio
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
