<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $amount;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $clusterAmount;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $Visible;

    #[ORM\Column(type: 'integer')]
    private $environmentId;

    #[ORM\Column(type: 'integer')]
    private $donatedAmount;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getClusterAmount(): ?int
    {
        return $this->clusterAmount;
    }

    public function setClusterAmount(int $clusterAmount): self
    {
        $this->clusterAmount = $clusterAmount;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->Visible;
    }

    public function setVisible(?bool $Visible): self
    {
        $this->Visible = $Visible;

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

    public function getDonatedAmount(): ?int
    {
        return $this->donatedAmount;
    }

    public function setDonatedAmount(int $donatedAmount): self
    {
        $this->donatedAmount = $donatedAmount;

        return $this;
    }
}
