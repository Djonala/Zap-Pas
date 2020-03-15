<?php


namespace App\Manager;


use App\Entity\EventView;
use App\Entity\Calendrier;
use App\Entity\EventZimbra;
use App\Repository\EventZimbraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use App\Repository;


/**
 * Class CalendarManager
 * @package App\Manager
 */
class CalendarManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CalendarManager constructor.
     */
    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function synchroCalendar(Calendrier $calendar) {
        $url = $calendar->getUrl();
        try {
            $parsed_json = $this->parseJsonToPhpArray($url);
            // RECUPERATION DU TABLEAU D'EVENEMENT APPT DANS LE TABLEAU DU FICHIER JSON
            try {
                $ar_events= $this->selectArrayFileJson($parsed_json,'appt');

                // ON BOUCLE SUR TOUS LES EVENEMENTS DU NOUVEAU TABLEAU
                foreach ($ar_events as $event) {
                    $repo = $this->entityManager->getRepository(EventZimbraRepository::class)->findBy(
                        ['id_zimbra'=> $event{'id'}, 'calendrier_id'=> $calendar->getId()]
                    );

                    // SI L'OBJET EST INEXISTANT EN BDD
                    if(!$repo){
                        $coursZimbra = $this->createNewEventZimbra($event);
                        $calendrier->addEventsZimbra($coursZimbra);
                        // ENREGISTREMENT EN BDD
                        $this->entityManager->persist($coursZimbra);
                        $this->entityManager->persist($calendrier);

                        // SI LE TITRE N'EST PAS A JOUR
                    } else if ($repo->getMatiere() != $this->getTitleFromZimbra($event)) {
                        $titre=$this->getTitleFromZimbra($event);
                        $repo->setMatiere($titre);

                        // SI L'HEURE DE DEBUT N'EST PAS A JOUR
                    } else if($repo->getDateDebutEvent() != $this->getDateBeginFromZimbra($event)) {
                        $dateHeureDebut=$this->getDateBeginFromZimbra($event);
                        $repo->setDateDebutEvent($dateHeureDebut);

                        // SI L'ID ZIMBRA N'EST PAS A JOUR
                    } else if($repo->getIdZimbra() != $this->getIdFromZimbra($event)) {
                        $id=$this->getIdFromZimbra($event);
                        $repo->setIdZimbra($id);

                        // SI LA DATE DE FIN N'EST PAS A JOUR
                    } else if($repo->getDateFinEvent() != $this->getDateFinFromZimbra($event)) {
                        $dateHeureFin=$this->getDateFinFromZimbra($event);
                        $repo->setDateFinEvent($dateHeureFin);

                        // SI LE LIEU N'EST PAS A JOUR
                    } else if($repo->getLieu() != $this->getLieuFromZimbra($event)) {
                        $lieu = $this->getLieuFromZimbra($event);
                        $repo->setLieu($lieu);

                        // SI LE MAIL DE L'INTERVENANT N'EST PAS A JOUR
                    } else if($repo->getEmailIntervenant() != $this->getMailIntervenantFromZimbra($event)) {
                        $mailIntervenant = $this->getMailIntervenantFromZimbra($event);
                        $repo->setEmailIntervenant($mailIntervenant);

                        // SI LE NOM DE L'INTERVENANT N'EST PAS A JOUR
                    } else if($repo->getNomFormateur() != $this->getNomIntervenantFromZimbra($event)){
                        $nomIntervenant = $this->getNomIntervenantFromZimbra($event);
                        $repo->setNomFormateur($nomIntervenant);
                    }
                    // VALIDATION DE L'ENREGISTREMENT (UPDATE)
                    $this->entityManager->flush();
                }

            } catch (Exception $exception) {
                echo $exception->getMessage();
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

    }

    /**
     * Fonction qui selectionne les éléments nécessaires à l'affichage dans un fichier .json et qui renvoi un fichier json
     * @param string $url
     * @return array
     * @throws Exception
     */
    public function initCalendarZimbra(Calendrier $calendrier){
        // RECUPERATION DE L'URL
        $url = $calendrier->getUrl();
        try {
            // PARSE DU FICHIER JSON EN ARRAY PHP
            $parsed_json = $this->parseJsonToPhpArray($url);
            try {
                // RECUPERATION DU TABLEAU D'EVENEMENT APPT DANS LE TABLEAU DU FICHIER JSON
                $ar_events= $this->selectArrayFileJson($parsed_json,'appt');
                /**
                 * Pour chaque ligne du tableau d'evenement
                 * recupération des différents élements nécessaires
                 * au traitement et à l'affichage
                 */
                foreach ($ar_events as $event) {
                    $coursZimbra = $this->createNewEventZimbra($event);
                    $calendrier->addEventsZimbra($coursZimbra);
                    // LANCEMENT INSERT
                    $this->entityManager->persist($coursZimbra);
                    $this->entityManager->persist($calendrier);
                }
                // LANCEMENT UPDATE
                $this->entityManager->flush();
            } catch (Exception $exception) {
                echo $exception->getMessage();
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }


    /**
     * Fonction qui parse un fichier JSON en array PHP
     * @param string $url
     * @return mixed
     * @throws Exception
     */
    public function parseJsonToPhpArray(string $url){
        if(!file_get_contents($url)){
            throw new Exception('Le lien URL est incorrect');
        }
        $json = file_get_contents($url); // Recupération du fichier json via l'url du calendrier passé en param
        $parsed_json = json_decode($json, true);    // parse du fichier json en tableau PHP
        return $parsed_json;
    }


    /** Fonction qui selectionne une ligne du tableau PHP (elle même un tableau)
     * @param array $arrayPHP
     * @param string $selector
     * @return mixed
     * @throws Exception
     */
    public function selectArrayFileJson(array $arrayPHP, string $selector){
        if (!$arrayPHP){
            throw new Exception('Le tableau est vide');
        }
        if (!$ar_select = $arrayPHP[$selector]) {
            throw new Exception('Le selecteur n\'est pas retrouvé dans le tableau ');
        }
        $ar_select = $arrayPHP[$selector];
        return $ar_select;
    }

    /** Fonction qui parse un array en JSON
     * @param array $arPHP
     * @return false|string
     */
    public function parsePhpToJsonFile(array $arPHP){
        $arJSON = json_encode($arPHP);
        return $arJSON;
    }

    public function getTitleFromZimbra($event) {
        // RECUPERATION DU TITRE DE L'EVENT
        if (isset($event{'inv'}[0]{'comp'}[0]{'name'})) {
            $titre = $event{'inv'}[0]{'comp'}[0]{'name'};
        } else {
            $titre = "titre non défini";
        }
        return $titre;
    }

    public function getDateBeginFromZimbra($event) {
        // RECUPERATION DE LA DATE DE DEBUT DE L'EVENT
        if (isset($event{'inv'}[0]{'comp'}[0]{'s'}[0]{'d'})) {
            $dBegin = $event{'inv'}[0]{'comp'}[0]{'s'}[0]{'d'};
            $dateHeureDebut = \DateTime::createFromFormat('Ymd\THis', $dBegin);
        } else {
            throw new Exception('Date de debut non communiquée'); // Une date de début non définie renvoi une exception
        }
        return $dateHeureDebut;
    }


    public function getDateFinFromZimbra($event){
        // RECUPERATION DE LA DATE DE FIN DE L'EVENT
        if (isset($event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'})) {
            $dEnd = $event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'};
            $dateHeureFin = \DateTime::createFromFormat('Ymd\THis', $dEnd);
        } else {
            throw new Exception('Date de fin non communiquée'); // une date de fin non définie renvoi une exception
        }

        return $dateHeureFin;
    }

    public function getIdFromZimbra($event){
        // RECUPERATION DE L'ID ZIMBRA DE LEVENT
        if (isset($event{'id'})) {
            $id = $event{'id'};
        } else {
            throw new Exception('ID ZIMBRA Inexistant'); // Un id zimbra non défini renvoi une exception
        }
        return $id;
    }

    public function getLieuFromZimbra($event){
        // RECUPERATION DU LIEU DE L'EVENT
        if (isset($event{'inv'}[0]{'comp'}[0]{'loc'})) {
            $lieu = $event{'inv'}[0]{'comp'}[0]{'loc'};
        } else {
            $lieu = 'lieu non renseigné';
        }
        return $lieu ;
    }

    public function getMailIntervenantFromZimbra($event){
        // RECUPERATION DU MAIL DE L'INTERVENANT
        if (isset($event{'inv'}[0]{'comp'}[0]{'at'}[0]{'a'})) {
            $mailIntervenant = $event{'inv'}[0]{'comp'}[0]{'at'}[0]{'a'};
        } else {
            $mailIntervenant = 'mail non renseigné';
        }
        return $mailIntervenant;
    }


    public function getNomIntervenantFromZimbra($event){
        //RECUPERATION DU NOM DE L'INTERVENANT
        if (isset($event{'inv'}[0]{'comp'}[0]{'fr'})) {
            $nomIntervenant = $event{'inv'}[0]{'comp'}[0]{'fr'};
        } else {
            $nomIntervenant = 'nom de l\'intervenant non renseigné';
        }
        return $nomIntervenant;
    }

    public function createNewEventZimbra($event) {
        $coursZimbra = new EventZimbra();

        $titre=$this->getTitleFromZimbra($event);
        $coursZimbra->setMatiere($titre);

        $dateHeureDebut=$this->getDateBeginFromZimbra($event);
        $coursZimbra->setDateDebutEvent($dateHeureDebut);

        $id=$this->getIdFromZimbra($event);
        $coursZimbra->setIdZimbra($id);

        $dateHeureFin=$this->getDateFinFromZimbra($event);
        $coursZimbra->setDateFinEvent($dateHeureFin);

        $lieu = $this->getLieuFromZimbra($event);
        $coursZimbra->setLieu($lieu);

        $mailIntervenant = $this->getMailIntervenantFromZimbra($event);
        $coursZimbra->setEmailIntervenant($mailIntervenant);

        $nomIntervenant = $this->getNomIntervenantFromZimbra($event);
        $coursZimbra->setNomFormateur($nomIntervenant);

        return $coursZimbra;

    }

}