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

    public function  __construct(string $nomCal, string $urlJsonCal, $classCal, $formatterCal, $adminCal, $agentAdminCal)
    {
        $this->nom = $nomCal;
        $this->url = $urlJsonCal;
        $this->classe = $classCal;
        $this->formateurs=$formatterCal;
        $this->admin = $adminCal;
        $this->administratifs=$agentAdminCal;
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

    public function getUrl(): ?array
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

    public function initCalendarZimbra() {
         $json = file_get_contents($this->url);
        $parsed_json= json_decode($json,true);
        $appt = $parsed_json['appt'];
        $parsed_json = json_decode($json);
        $i=0;

        foreach ($appt as $value) {
            //Attention aux erreurs ici (!!!!!!!!!!) WARNING (!!!!!!!!!!!!!!!)
            //Les valeurs doivent être try..catch
            $id = $parsed_json->{'appt'}[$i]->{'id'};
            $nom = $parsed_json->{'appt'}[$i]->{'inv'}[0]->{'comp'}[0]->{'name'};
            $lieu = $parsed_json->{'appt'}[$i]->{'inv'}[0]->{'comp'}[0]->{'loc'};
            $mailAnimateur = $parsed_json->{'appt'}[$i]->{'inv'}[0]->{'comp'}[0]->{'at'}[0]->{'a'};
            //$description1 = $parsed_json->{'appt'}[$i]->{'inv'}[0]->{'comp'}[0]->{'fr'};
            $description2 = $parsed_json->{'appt'}[0]->{'inv'}[0]->{'comp'}[0]->{'desc'}[0]->{'_content'};
            $dBegin = $parsed_json->{'appt'}[$i]->{'inv'}[0]->{'comp'}[0]->{'s'}[0]->{'d'};
            $dateDebut = DateTime::createFromFormat('Ymd\THis', $dBegin)->format('d/m/Y H:i:s');
            $dEnd = $parsed_json->{'appt'}[$i]->{'inv'}[0]->{'comp'}[0]->{'e'}[0]->{'d'};
            $dateFin = DateTime::createFromFormat('Ymd\THis', $dEnd)->format('d/m/Y H:i:s');

            $i++;
            //Attention au constructeur ici (!!!!!!!!!!) WARNING (!!!!!!!!!!!!!!!)
            //Les valeurs sont incorrecte & pas toutes utilisées
            $cours = new CoursZimbra($nom, $dateDebut->format('d/m/Y'), $dateDebut->format('H:i:s'), $dateFin->format('H:i:s'), $mailAnimateur, $lieu, $mailAnimateur,$description2);
            $this->eventZimbra[] = $cours;
        }


    }
}
