<?php

namespace App\Repository;

use App\Entity\EventView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EventView|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventView|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventView[]    findAll()
 * @method EventView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventView::class);
    }

    // /**
    //  * @return EventView[] Returns an array of EventView objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventView
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
