<?php


namespace App\Manager;


use App\Entity\EventView;
use App\Entity\Calendrier;
use App\Entity\EventZimbra;
use App\Repository\EventZimbraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;


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
        //instantation d'un booléen pour declencher l'envoi d'un mail
        $isModify = false;

        //recuperation de l'url du calendrier transmis
        $url = $calendar->getUrl();
        try {
            //transforme le fichier json en array php
            $parsed_json = $this->parseJsonToPhpArray($url);

            try {
                // RECUPERATION DU TABLEAU D'EVENEMENT APPT DANS LE TABLEAU DU FICHIER JSON
                $ar_api_eventZimbra= $this->selectArrayFileJson($parsed_json,'appt');

                // j'accede au repository
                $repo = $this->entityManager->getRepository(EventZimbra::class);

                // je recupère l'ensemble des objets type eventzimbra en bdd
                $objs_db_eventZimbra = $repo->findAll();
                // j'instancie un tableau vide
                $ar_db_idZimbra = [];

                // je boucle sur tous les objets et je recupère uniquement l'str_db_idZimbra que je place dans un tableau
                foreach ($objs_db_eventZimbra as $obj_db_eventZimbra) {
                    $ar_db_idZimbra[] = [
                        'id'=>$obj_db_eventZimbra->getIdZimbra()
                    ];
                }

                // Boucle sur tous les ID contenus dans le tableau
                foreach ($ar_db_idZimbra as $str_db_idZimbra) {
                    // pour chaque tour, j'init le validator à 'false'
                    $validator = false;
                    // pour chaque id, je boucle sur le tableau récupéré depuis l'api et je verifie la correspondance
                    foreach ($ar_api_eventZimbra as $element) {
                        //si l'id correspond à celui en base, validator prend la valeur 'true'
                        // ici l'optimisation n'est pas maximale, parceque l'algorithme continue de verifier des valeurs qui ont déjà été validées
                        if($element{'id'}==$str_db_idZimbra{'id'}) {
                            $validator = true;
                        }
                    }
                    // si le validator a la valeur 'false' soit si aucune correspondance n'a été trouvée dans les 2 tableaux d'id
                    //on recupère l'objet en bdd et on le supprime.
                    if($validator===false){
                        $obj_db_eventZimbra= $repo->findOneBy(['idZimbra'=>$str_db_idZimbra{'id'}]);
                        $this->entityManager->remove($obj_db_eventZimbra);
                    }
                }

                // ON BOUCLE SUR TOUS LES EVENEMENTS DU NOUVEAU TABLEAU (VERIF MODIF ET AJOUT)-----------------------
                foreach ($ar_api_eventZimbra as $event) {
                    $isModify = false;
                    $event_db= $repo->findOneBy([
                        'idZimbra'=> $event{'id'},
                        'calendrier'=> $calendar->getId(),
                    ]);

                    // SI L'OBJET EST INEXISTANT EN BDD
                    if(!$event_db){
                        $coursZimbra = $this->createNewEventZimbra($event);

                        $calendar->addEventsZimbra($coursZimbra);
                        // ENREGISTREMENT EN BDD
                        $this->entityManager->persist($coursZimbra);
                        $this->entityManager->persist($calendar);

                            // instantation d'un message pour le mail
                        $message = "Un nouvelle evenement a été ajouté : <br\>".$coursZimbra->toString();
                            // envoi du mail
                        $this->sendMail($message, $mailer, $calendar, $coursZimbra);

                        // SI LE TITRE N'EST PAS A JOUR
                    } else if ($event_db->getMatiere() != $this->getTitleFromZimbra($event)) {
                        $titre=$this->getTitleFromZimbra($event);
                        $event_db->setMatiere($titre);
                        $isModify = true;

                        // SI L'HEURE DE DEBUT N'EST PAS A JOUR
                    } else if($event_db->getDateDebutEvent() != $this->getDateBeginFromZimbra($event)) {
                        $dateHeureDebut=$this->getDateBeginFromZimbra($event);
                        $event_db->setDateDebutEvent($dateHeureDebut);
                        $isModify = true;

                        // SI LA DATE DE FIN N'EST PAS A JOUR
                    } else if($event_db->getDateFinEvent() != $this->getDateFinFromZimbra($event)) {
                        $dateHeureFin=$this->getDateFinFromZimbra($event);
                        $event_db->setDateFinEvent($dateHeureFin);
                        $isModify = true;

                        // SI LE LIEU N'EST PAS A JOUR
                    } else if($event_db->getLieu() != $this->getLieuFromZimbra($event)) {
                        $lieu = $this->getLieuFromZimbra($event);
                        $event_db->setLieu($lieu);
                        $isModify = true;

                        // SI LE MAIL DE L'INTERVENANT N'EST PAS A JOUR
                    } else if($event_db->getEmailIntervenant() != $this->getMailIntervenantFromZimbra($event)) {
                        $mailIntervenant = $this->getMailIntervenantFromZimbra($event);
                        $event_db->setEmailIntervenant($mailIntervenant);
                        $isModify = true;

                        // SI LE NOM DE L'INTERVENANT N'EST PAS A JOUR
                    } else if($event_db->getNomFormateur() != $this->getNomIntervenantFromZimbra($event)){
                        $nomIntervenant = $this->getNomIntervenantFromZimbra($event);
                        $event_db->setNomFormateur($nomIntervenant);
                        $isModify = true;
                    }
                    if($isModify){
                        // instantation du message pour le mail
                        $message = "Un evenement a été modifié : <br\>".$event_db->toString();

                        // envoi du mail
                        $this->sendMail($message, $mailer, $calendar, $event_db);
                    }
                }

                // VALIDATION DE L'ENREGISTREMENT (UPDATE)-----------------------------------------------------
                $this->entityManager->flush();
                echo('Calendrier '.$calendar->getNom().' id : '.$calendar->getId().' synchronisé');
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

    private function sendMail(string $body, \Swift_Mailer $mailer, Calendrier $calendrier, EventZimbra $eventZimbra)
    {
        //Recupération des utlisateurs de l'agenda
        $users = $calendrier->getUsers();
        $mailIntervenant = $eventZimbra->getEmailIntervenant();


        //Pour tous les utilisateurs de cet agenda
        foreach ($users as $user) {
            //SI l'utilisateur est stagiaire ou s'il est le formateur responsable du cours
            if($user->getRoles()==='ROLE_STAGIAIRE' || $user->getEmail()===$mailIntervenant) {

                // si l'utilisateur accepte l'envoi de notification par mail
                if ($user->getParameters()->getAutorizedSendMail() === true) {
                    $message = (new \Swift_Message('Notification : Motification d\'un evenement.'))
                        // on instancie un format
                        ->setFormat('votre@adresse.fr')

                        // On attribue le destinataire
                        ->setTo($user->getEmail())

                        // On créé le message
                        ->setBody(
                            $this->renderView(
                                'Mailer.notification_changement_agenda.html.twig', ['message' => $body]
                            ),
                            'text/html'
                        );
                    // On envoie le message
                    $mailer->send($message);
                }
            }
        }
    }
}
