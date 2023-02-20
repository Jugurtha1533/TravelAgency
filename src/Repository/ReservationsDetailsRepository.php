<?php

namespace App\Repository;

use App\Entity\ReservationsDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationsDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationsDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationsDetails[]    findAll()
 * @method ReservationsDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationsDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationsDetails::class);
    }

    // /**
    //  * @return ReservationsDetails[] Returns an array of ReservationsDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReservationsDetails
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
