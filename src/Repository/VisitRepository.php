<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    public function findVisitTomorrow()
    {
        $dateTime = new \DateTime();
        $dateTime->modify('+1 day');
        $startDateOfToday = $dateTime->format('Y-m-d') . ' 00:00:00';
        $endDateOfToday = $dateTime->format('Y-m-d') . ' 23:59:59';

        $dql = "
        SELECT
            u.id, 
            u.email,
            u.name,
            u.lastname,
            p.visitDate
         FROM App\Entity\User u 
         LEFT JOIN u.visits p
         WHERE
         p.status = 0 AND
         p.visitDate BETWEEN '" . $startDateOfToday . "' AND '" . $endDateOfToday."'";


        $query = $this->getEntityManager()->createQuery($dql);

        return  $query->execute();
    }
}
