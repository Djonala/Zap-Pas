<?php

namespace App\Entity;

use Cassandra\Date;
use Cassandra\Time;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoursZimbraRepository")
 */
class EventZimbra
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
    private $matiere;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomFormateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailIntervenant;


    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebutEvent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFinEvent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Calendrier", inversedBy="eventsZimbra")
     */
    private $calendrier;


    public function __construct()
    {

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }


    public function getNomFormateur(): ?string
    {
        return $this->nomFormateur;
    }

    public function setNomFormateur(string $nomFormateur): self
    {
        $this->nomFormateur = $nomFormateur;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEmailIntervenant(): ?string
    {
        return $this->emailIntervenant;
    }

    public function setEmailIntervenant(string $emailIntervenant): self
    {
        $this->emailIntervenant = $emailIntervenant;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateDebutEvent()
    {
        return $this->dateDebutEvent;
    }



    public function setDateDebutEvent( DateTimeInterface $dateDebutEvent): self
    {
        $this->dateDebutEvent = $dateDebutEvent;

        return $this;
    }

    public function getDateFinEvent(): ?DateTimeInterface
    {
        return $this->dateFinEvent;
    }

    public function setDateFinEvent(DateTimeInterface $dateFinEvent): self
    {
        $this->dateFinEvent = $dateFinEvent;

        return $this;
    }

    public function getCalendrier(): ?Calendrier
    {
        return $this->calendrier;
    }

    public function setCalendrier(?Calendrier $calendrier): self
    {
        $this->calendrier = $calendrier;

        return $this;
    }
}
