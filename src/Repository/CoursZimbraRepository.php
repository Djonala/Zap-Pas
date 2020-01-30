<?php

namespace App\Repository;

use App\Entity\CoursZimbra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CoursZimbra|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursZimbra|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursZimbra[]    findAll()
 * @method CoursZimbra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursZimbraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursZimbra::class);
    }

    // /**
    //  * @return CoursZimbra[] Returns an array of CoursZimbra objects
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
    public function findOneBySomeField($value): ?CoursZimbra
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
