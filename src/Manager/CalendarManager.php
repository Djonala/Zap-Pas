<?php


namespace App\Manager;


use App\Entity\Calendrier;
use App\Entity\CoursZimbra;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

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
     * @param $calendar Calendrier url du calendrier
     * Cette fonction est à utiliser au moment de la construction de l'objet pour générer la première version des
     * evenements zimbra du calendrier.
     * @throws Exception
     */
    public function initCalendarZimbra(Calendrier $calendar) {
        if(!file_get_contents($calendar->getUrl())){
            throw new Exception('Le lien URL est incorrect');
        }
        $json = file_get_contents($calendar->getUrl()); // Recupération du fichier json via l'url du calendrier passé en param
        $parsed_json = json_decode($json, true);    // parse du fichier json en tableau PHP
        $ar_evenements = $parsed_json['appt'];   //recupération du grand tableau appt qui contient l'ensemble des events
        $arrayEventZimbra = array();

        foreach ($ar_evenements as $event) {
//            if (isset($event{'id'})) {
//                $id = $event{'id'};
//            } else {
//                $id = "id non trouvé ";
//            }

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
                $heureDebut = "heure debut non precisée";
            }

            if (isset($event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'})) {
                $dEnd = $event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'};
                $dateFin = \DateTime::createFromFormat('Ymd\THis', $dEnd)->format('d/m/Y');
                $heureFin = \DateTime::createFromFormat('Ymd\THis', $dEnd)->format('H:i:s');
            } else {
                $dateFin = "date fin non precisée";
                $heureFin = "heure fin non precisée";
            }
            $cours = new CoursZimbra($titre, $dateDebut, $heureDebut, $heureFin, $mailAnimateur, $lieu, $mailAnimateur, $description1);
            $arrayEventZimbra[] = $cours;
        }
    }

    public function synchroCalendar(Calendrier $calendar) {


    }

    /**
     * Fonction qui selectionne les éléments nécessaires à l'affichage dans un fichier .json et qui renvoi un fichier json
     * @param json $url
     * @return array
     * @throws Exception
     */
    public function creationEventsZimbraFC(json $url){

        // PARSE DU FICHIER JSON EN ARRAY PHP
        try {
            $parsed_json = $this->parseJsonToPhpArray($url);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        // RECUPERATION DU TABLEAU D'EVENEMENT APPT DANS LE TABLEAU DU FICHIER JSON
        try {
            $ar_events= $this->selectArrayFileJson($parsed_json,'appt');
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        $arrayEventZimbra = array();

        /**
         * Pour chaque ligne du tableau d'evenement
         * recupération des différents élements nécessaires
         * au traitement et à l'affichage
         */
        foreach ($ar_events as $event) {

            // RECUPERATION DU TITRE DE L'EVENT
            if (isset($event{'inv'}[0]{'comp'}[0]{'name'})) {
                $titre = $event{'inv'}[0]{'comp'}[0]{'name'};
            } else {
                $titre = "titre non défini";
            }

            // RECUPERATION DE LA DATE DE DEBUT DE L'EVENT
            if (isset($event{'inv'}[0]{'comp'}[0]{'s'}[0]{'d'})) {
                $dBegin = $event{'inv'}[0]{'comp'}[0]{'s'}[0]{'d'};
                $dateHeureDebut = \DateTime::createFromFormat('Ymd\THis', $dBegin)->format('Y-m-d H:i:s');
            } else {
                throw new Exception('Date de debut non communiquée'); // Une date de début non définie renvoi une exception
            }

            // RECUPERATION DE LA DATE DE FIN DE L'EVENT
            if (isset($event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'})) {
                $dEnd = $event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'};
                $dateHeureFin = \DateTime::createFromFormat('Ymd\THis', $dEnd)->format('Y-m-d H:i:s');
            } else {
                throw new Exception('Date de fin non communiquée'); // une date de fin non définie renvoi une exception
            }

            // ASSEMBLAGE DE L'ENSEMBLE DANS UN OBJET
            $myObj->titre = $titre;
            $myObj->start = $dBegin;
            $myObj->end = $dEnd;

            // AJOUT DANS UN TABLEAU FINAL
            $arrayEventZimbra[] = $myObj;
        }

        // PARSE DU TABLEAU EN JSON POUR AFFICHAGE
        $myJson= $this->parsePhpToJsonFile($arrayEventZimbra);

        return $myJson;
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

}