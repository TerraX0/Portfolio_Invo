<?php

namespace App\Entity;

use App\Repository\AuslaenderbehoerdenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuslaenderbehoerdenRepository::class)]
class Auslaenderbehoerden
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $schluessel = null;

    #[ORM\Column(length: 255)]
    private ?string $bezeichnung = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchluessel(): ?string
    {
        return $this->schluessel;
    }

    public function setSchluessel(string $schluessel): static
    {
        $this->schluessel = $schluessel;

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
