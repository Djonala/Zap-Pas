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

    /**Fonction qui selectionne en bdd les eventZimbra dont l'idZimbra sera donné et à laquelle le calendrier correspond
//     * @param $idZimbra
//     * @return mixed|null
//     * @throws \Doctrine\ORM\NonUniqueResultException
//     */

        public function findAllByIdJoinedToCalendrier($idCal) {
            $query = $this->getEntityManager()
                ->createQuery(
                    'SELECT * FROM zappas1:event_zimbra
            INNER JOIN calendrier c ON c.id = event_zimbra.calendrier_id
            WHERE event_zimbra.id = :idCal'
                )->setParameter('idCal', $idCal);

            try {
                return $query->getResult();
            } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
            }
        }
//    public function findOneByIdJoinedToCalendrier($idZimbra)
//    {
//        $query = $this->getEntityManager()
//            ->createQuery(
//                'SELECT event FROM AppBundle:Product p
//        JOIN p.category c
//        WHERE p.id = :id'
//            )->setParameter('id', $productId);
//
//        try {
//            return $query->getSingleResult();
//        } catch (\Doctrine\ORM\NoResultException $e) {
//            return null;
//        }
//    }
}