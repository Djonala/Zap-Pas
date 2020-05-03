<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserParametersRepository")
 */
class UserParameters
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $autorizedSendMail;

    /**
     * UserParameters constructor.
     * @param $autorizedSendMail
     */
    public function __construct($autorizedSendMail)
    {
        $this->autorizedSendMail = $autorizedSendMail;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAutorizedSendMail(): ?bool
    {
        return $this->autorizedSendMail;
    }

    public function setAutorizedSendMail(bool $autorizedSendMail): self
    {
        $this->autorizedSendMail = $autorizedSendMail;

        return $this;
    }
}
