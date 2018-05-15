<?php

namespace App\Repository;

use App\Entity\Sorteo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sorteo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorteo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorteo[]    findAll()
 * @method Sorteo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SorteoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sorteo::class);
    }

    /**
     * @param $min, $max
     * @return Sorteo[]
     */
    public function findBetween($max, $min): array
    {
        $qb = $this->createQueryBuilder('sor')
            ->Where('sor.id BETWEEN :min AND :max')
            ->setParameter('min', $max)
            ->setParameter('max', $min)
            ->orderBy('sor.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

//    /**
//     * @return Sorteo[] Returns an array of Sorteo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sorteo
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
