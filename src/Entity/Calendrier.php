<?php

namespace App\Entity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CalendrierRepository")
 */
class Calendrier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="json")
     */
    private $docPersistJson = [];

    /**
     * @ORM\Column(type="array")
     */
    private $classe = [];

    /**
     * @ORM\Column(type="array")
     */
    private $formateurs = [];

    /**
     * @ORM\Column(type="object")
     */
    private $admin;

    /**
     * @ORM\Column(type="array")
     */
    private $administratifs = [];

    /**
     * @ORM\Column(type="array")
     */
    private $eventZimbra = [];

    /**
     * @ORM\Column(type="array")
     */
    private $eventLocal = [];

    public function __construct(string $nomCal, string $urlJsonCal, $classCal, $formatterCal, $adminCal, $agentAdminCal)
    {
        $this->nom = $nomCal;
        $this->url = $urlJsonCal;
        $this->classe = $classCal;
        $this->formateurs = $formatterCal;
        $this->admin = $adminCal;
        $this->administratifs = $agentAdminCal;
        $this->docPersistJson = $this->initCalendarZimbra($this->url);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;

    }

    public function setUrl(array $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDocPersistJson(): ?array
    {
        return $this->docPersistJson;
    }

    public function setDocPersistJson(array $docPersistJson): self
    {
        $this->docPersistJson = $docPersistJson;

        return $this;
    }

    public function getClasse(): ?array
    {
        return $this->classe;
    }

    public function setClasse(array $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getFormateurs(): ?array
    {
        return $this->formateurs;
    }

    public function setFormateurs(array $formateurs): self
    {
        $this->formateurs = $formateurs;

        return $this;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function setAdmin($admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getAdministratifs(): ?array
    {
        return $this->administratifs;
    }

    public function setAdministratifs(array $administratifs): self
    {
        $this->administratifs = $administratifs;

        return $this;
    }

    public function getEventZimbra(): ?array
    {
        return $this->eventZimbra;
    }

    public function setEventZimbra(array $eventZimbra): self
    {
        $this->eventZimbra = $eventZimbra;

        return $this;
    }

    public function getEventLocal(): ?array
    {
        return $this->eventLocal;
    }

    public function setEventLocal(array $eventLocal): self
    {
        $this->eventLocal = $eventLocal;

        return $this;
    }

    /**
     * @param url du calendrier
     * @return array de CoursZimbra
     * Cette fonction est à utiliser au moment de la construction de l'objet pour générer la première version des
     * evenements zimbra du calendrier.
     */
    public function initCalendarZimbra($url) {
        $json = file_get_contents($url); // Recupération du fichier json via l'url
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
                $dateDebut = DateTime::createFromFormat('Ymd\THis', $dBegin)->format('d/m/Y');
                $heureDebut = DateTime::createFromFormat('Ymd\THis', $dBegin)->format('H:i:s');
            } else {
                $dateDebut = "date debut non precisée";
                $heureDebut = "heure debut non precisé";
            }

            if (isset($event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'})) {
                $dEnd = $event{'inv'}[0]{'comp'}[0]{'e'}[0]{'d'};
                $dateFin = DateTime::createFromFormat('Ymd\THis', $dEnd)->format('d/m/Y');
                $heureFin = DateTime::createFromFormat('Ymd\THis', $dEnd)->format('H:i:s');
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
