<?php

namespace App\Entity;

use App\Repository\IPKennerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IPKennerRepository::class)]
class IPKenner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $kuerzel = null;

    #[ORM\Column(length: 255)]
    private ?string $bezeichnung = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKuerzel(): ?string
    {
        return $this->kuerzel;
    }

    public function setKuerzel(string $kuerzel): static
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
}
