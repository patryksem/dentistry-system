<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param string $role
     * @return User[]
     */
    public function findOneByRole(string $role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_' . $role . '%')
            ->getQuery()
            ->getResult();
    }

    public function findPatientsBookedAtDate(User $doctor, \DateTime $dateTime)
    {
        $startDateOfToday = $dateTime->format('Y-m-d') . ' 00:00:00';
        $endDateOfToday = $dateTime->format('Y-m-d') . ' 23:59:59';

        $dql = "
        SELECT
            u.id, 
            p.visitDate
         FROM App\Entity\User u 
         LEFT JOIN u.patients p
         WHERE
         p.visitDate BETWEEN '" . $startDateOfToday . "' AND '" . $endDateOfToday . "' AND 
         u.id = ".$doctor->getId();

        $query = $this->getEntityManager()->createQuery($dql);
        return  $query->execute();
    }

}
