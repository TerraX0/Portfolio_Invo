<?php

namespace App\Entity;

use App\Repository\NationenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NationenRepository::class)]
class Nationen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $kuerzel = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $bezeichnung = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $kfz = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $nr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKuerzel(): ?string
    {
        return $this->kuerzel;
    }

    public function setKuerzel(?string $kuerzel): static
    {
        $this->kuerzel = $kuerzel;

        return $this;
    }

    public function getBezeichnung(): ?string
    {
        return $this->bezeichnung;
    }

    public function setBezeichnung(string $bezeichnung): static
    {
        $this->bezeichnung = $bezeichnung;

        return $this;
    }

    public function getKfz(): ?string
    {
        return $this->kfz;
    }

    public function setKfz(?string $kfz): static
    {
        $this->kfz = $kfz;

        return $this;
    }

    public function getNr(): ?string
    {
        return $this->nr;
    }

    public function setNr(?string $nr): static
    {
        $this->nr = $nr;

        return $this;
    }
}
