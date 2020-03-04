<?php

namespace App\Repository;

use App\Entity\EventZimbra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EventZimbra|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventZimbra|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventZimbra[]    findAll()
 * @method EventZimbra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursZimbraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventZimbra::class);
    }

    // /**
    //  * @return EventZimbra[] Returns an array of EventZimbra objects
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
    public function findOneBySomeField($value): ?EventZimbra
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
