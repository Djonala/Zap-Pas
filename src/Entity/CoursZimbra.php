<?php

namespace App\Entity;

use Cassandra\Date;
use Cassandra\Time;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoursZimbraRepository")
 */
class CoursZimbra
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
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $heureDebut;

    /**
     * @ORM\Column(type="time")
     */
    private $heureFin;

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
     * @ORM\Column(type="string", length=255)
     */
    private $descriptionEvent;


    public function __construct(string $titreEvent, $dateEvent, $HDebutEvent, $HFinEvent, string $nomProfEvent, string $lieuEvent, string $emailProfEvent, string $description)
    {
        $this->matiere=$titreEvent;
        $this->date=$dateEvent;
        $this->heureDebut=$HDebutEvent;
        $this->heureFin=$HFinEvent;
        $this->nomFormateur=$nomProfEvent;
        $this->lieu=$lieuEvent;
        $this->emailIntervenant=$emailProfEvent;
        $this->descriptionEvent=$description;


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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTimeInterface $heureDebut): self
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTimeInterface $heureFin): self
    {
        $this->heureFin = $heureFin;

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

    public function getDescriptionEvent(): ?string
    {
        return $this->descriptionEvent;
    }

    public function setDescriptionEvent(string $descriptionEvent): self
    {
        $this->descriptionEvent = $descriptionEvent;

        return $this;
    }
}
