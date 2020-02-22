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

    /** titre de la formation
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /** url agenda transmis par l'admin
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /** (!)(!)(!) A VOIR SI VRAIMENT NECESSAIRE (!)(!)(!)
     * @ORM\Column(type="json")
     */
    private $docPersistJson = [];

    /**
     * la promotion
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

    public function setUrl(string $url): self
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


}
