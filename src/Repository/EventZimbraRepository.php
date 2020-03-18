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
class EventZimbraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventZimbra::class);
    }

}