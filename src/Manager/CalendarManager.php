<?php


namespace App\Manager;


use App\Entity\EventView;
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

    public function synchroCalendar(Calendrier $calendar) {
    }

    /**
     * Fonction qui selectionne les éléments nécessaires à l'affichage dans un fichier .json et qui renvoi un fichier json
     * @param string $url
     * @return array
     * @throws Exception
     */
    public function initCalendarZimbra(string $url){
     // PARSE DU FICHIER JSON EN ARRAY PHP
        try {
            $parsed_json = $this->parseJsonToPhpArray($url);
            // RECUPERATION DU TABLEAU D'EVENEMENT APPT DANS LE TABLEAU DU FICHIER JSON
            try {
                $ar_events= $this->selectArrayFileJson($parsed_json,'appt');


                /**
                 * Pour chaque ligne du tableau d'evenement
                 * recupération des différents élements nécessaires
                 * au traitement et à l'affichage
                 */
                foreach ($ar_events as $event) {
                    $coursZimbra = new CoursZimbra();

                    // RECUPERATION DU TITRE DE L'EVENT
                    if (isset($event{'inv'}[0]{'comp'}[0]{'name'})) {
                        $titre = $event{'inv'}[0]{'comp'}[0]{'name'};
                    } else {
                        $titre = "titre non défini";
                    }
                    $coursZimbra->setMatiere($titre);

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

                    // RECUPERATION DU LIEU DE L'EVENT
                    if (isset($event{'inv'}[0]{'comp'}[0]{'loc'})) {
                        $lieu = $event{'inv'}[0]{'comp'}[0]{'loc'};
                    } else {
                        $lieu = "Lieu non précisé";
                    }

                    // ENREGISTREMENT EN BDD
                    $this->entityManager->persist($coursZimbra);
                }
                // VALIDATION DE L'ENREGISTREMENT
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

}