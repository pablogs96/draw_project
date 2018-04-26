<?php

namespace App\Repository;

use App\Entity\Encuesta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Encuesta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Encuesta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Encuesta[]    findAll()
 * @method Encuesta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncuestaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Encuesta::class);
    }

//    /**
//     * @return Encuesta[] Returns an array of Encuesta objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Encuesta
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
