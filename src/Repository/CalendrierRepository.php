<?php

namespace App\Repository;

use App\Entity\Calendrier;
use App\Entity\EventZimbra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @method Calendrier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendrier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendrier[]    findAll()
 * @method Calendrier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendrierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendrier::class);
    }

    /**
     * @return mixed
     * @throws \Exception
     * fonction qui recherche l'ensemble des calendriers qui ont des évenements datant de moins de 2 mois
     */
    public function findAllCalAvailable(){
        $date = new \DateTime();
        $date->modify('-2 month');

        return $this->createQueryBuilder('c')
            ->join('App\Entity\EventZimbra', 'ez', Expr\Join::WITH, 'c.id = ez.calendrier')
            ->Where('ez.dateFinEvent >= :date')
            ->groupBy('c')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();

    }


}
