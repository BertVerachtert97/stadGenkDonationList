<?php

namespace App\Entity;

use App\Repository\DonatorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonatorRepository::class)]
class Donator
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $surname;

    #[ORM\Column(type: 'integer')]
    private $environmentId;

    #[ORM\Column(type: 'string', length: 255)]
    private $donation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEnvironmentId(): ?int
    {
        return $this->environmentId;
    }

    public function setEnvironmentId(int $environmentId): self
    {
        $this->environmentId = $environmentId;

        return $this;
    }

    public function getDonation(): ?string
    {
        return $this->donation;
    }

    public function setDonation(string $donation): self
    {
        $this->donation = $donation;

        return $this;
    }
}
