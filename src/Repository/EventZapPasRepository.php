<?php

namespace App\Repository;

use App\Entity\EventZapPas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EventZapPas|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventZapPas|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventZapPas[]    findAll()
 * @method EventZapPas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventZapPasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventZapPas::class);
    }

    // /**
    //  * @return EventZapPas[] Returns an array of EventZapPas objects
    //  */
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
    public function findOneBySomeField($value): ?EventZapPas
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
