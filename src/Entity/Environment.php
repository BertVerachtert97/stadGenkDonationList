<?php

namespace App\Entity;

class Environment
{
    private $id;

    private $name;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

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

    public function getEnvironments(): array
    {
        $kuurpunt = new Environment();
        $kuurpunt->setId(1);
        $kuurpunt->setName('Kuurpunt');

        $vrouwencentrum = new Environment();
        $vrouwencentrum->setId(2);
        $vrouwencentrum->setName('Vrouwencentrum');

        $nieuwDak = new Environment();
        $nieuwDak->setId(3);
        $nieuwDak->setName('Nieuw Dak');

        return [
            $kuurpunt,
            $nieuwDak,
            $vrouwencentrum
        ];
    }
}