<?php


namespace App\Manager;


use App\Entity\Calendrier;
use App\Entity\CoursZimbra;
use Doctrine\ORM\EntityManagerInterface;

class CalendarManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     * CalendarManager constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param $calendar url du calendrier
     * @return array de CoursZimbra
     * Cette fonction est à utiliser au moment de la construction de l'objet pour générer la première version des
     * evenements zimbra du calendrier.
     */
    public function initCalendarZimbra(Calendrier $calendar) {
        $json = file_get_contents($calendar->getUrl()); // Recupération du fichier json via l'url
        $parsed_json = json_decode($json, true);    // parse du fichier json en tableau PHP
        $ar_evenements = $parsed_json['appt'];   //recupération du grand tableau appt qui contient l'ensemble des events
        $arrayEventZimbra = array();

        foreach ($ar_evenements as $event) {
            if (isset($event{'id'})) {
                $id = $event{'id'};
            } else {
                $id = "id non trouvé ";
            }

            if (isset($event{'inv'}[0]{'comp'}[0]{'name'})) {
                $titre = $event{'inv'}[0]{'comp'}[0]{'name'};
            } else {
                $titre = "titre non défini";
            }


            if (isset($event{'inv'}[0]{'comp'}[0]{'loc'})) {
                $lieu = $event{'inv'}[0]{'comp'}[0]{'loc'};
            } else {
                $lieu = "Lieu non précisé";
            }

            if (isset($event{'inv'}[0]{'comp'}[0]{'at'}[0]{'a'})) {
                $mailAnimateur = $event{'inv'}[0]{'comp'}[0]{'at'}[0]{'a'};
            } else {
                $mailAnimateur = $event{'inv'}[0]{'comp'}[0]{'or'}{'a'};
            }

            if (isset($event{'inv'}[0]{'comp'}[0]{'fr'})) {
                $description1 = $event{'inv'}[0]{'comp'}[0]{'fr'};
            } else {
                $description1 = "";
            }

            if (isset($event{'inv'}[0]{'comp'}[0]{'s'}[0]{'d'})) {
                $dBegin = $event{'inv'}[0]{'comp'}[0]{'s'}[0]{'d'};
                $dateDebut = \DateTime::createFromFormat('Ymd\THis', $dBegin)->format('d/m/Y');
                $heureDebut = \DateTime::createFromFormat('Ymd\THis', $dBegin)->format('H:i:s');
            } else {
                $dateDebut = "date debut non precisée";
                $heureDebut = "heure debut non precisé";
            }

            if (isset($event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'})) {
                $dEnd = $event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'};
                $dateFin = \DateTime::createFromFormat('Ymd\THis', $dEnd)->format('d/m/Y');
                $heureFin = \DateTime::createFromFormat('Ymd\THis', $dEnd)->format('H:i:s');
            } else {
                $dateFin = "date fin non precisée";
                $heureFin = "heure fin non precisé";
            }
            $cours = new CoursZimbra($titre, $dateDebut, $heureDebut, $heureFin, $mailAnimateur, $lieu, $mailAnimateur, $description1);
            $arrayEventZimbra[] = $cours;
        }

        return $arrayEventZimbra;

    }



}