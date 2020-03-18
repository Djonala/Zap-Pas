<?php

namespace App\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventZimbra", mappedBy="calendrier", orphanRemoval=true, cascade={"persist", "remove", "merge"})
     */
    private $eventsZimbra;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Users", inversedBy="calendriers")
     */
    private $users;

    public function __construct()
    {
        $this->eventsZimbra = new ArrayCollection();
        $this->users = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): ?self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;

    }

    public function setUrl(string $url): ?self
    {
        $this->url = $url;

        return $this;
    }





    /**
     * @return Collection|EventZimbra[]
     */
    public function getEventsZimbra(): Collection
    {
        return $this->eventsZimbra;
    }

    public function addEventsZimbra(EventZimbra $eventsZimbra): self
    {
        if (!$this->eventsZimbra->contains($eventsZimbra)) {
            $this->eventsZimbra[] = $eventsZimbra;
            $eventsZimbra->setCalendrier($this);
        }

        return $this;
    }

    public function removeEventsZimbra(EventZimbra $eventsZimbra): self
    {
        if ($this->eventsZimbra->contains($eventsZimbra)) {
            $this->eventsZimbra->removeElement($eventsZimbra);
            // set the owning side to null (unless already changed)
            if ($eventsZimbra->getCalendrier() === $this) {
                $eventsZimbra->setCalendrier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): ?self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(Users $user): ?self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }



}
