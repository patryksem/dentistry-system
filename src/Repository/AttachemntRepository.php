<?php

namespace App\Repository;

use App\Entity\Attachemnt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Attachemnt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attachemnt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attachemnt[]    findAll()
 * @method Attachemnt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachemntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attachemnt::class);
    }

    // /**
    //  * @return Attachemnt[] Returns an array of Attachemnt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Attachemnt
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
