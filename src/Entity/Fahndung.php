<?php

namespace App\Entity;

use App\Repository\FahndungRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: FahndungRepository::class)]
#[UniqueEntity(fields: ['vorgangsnummer'], message: 'Ein Vorgang mit dieser Vorgangsnummer existiert bereits.')]
class Fahndung
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20,unique: true)]
    private ?string $vorgangsnummer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vorname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $gebdatum = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $loeschung = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $erfassung = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getVorname(): ?string
    {
        return $this->vorname;
    }

    public function setVorname(?string $vorname): static
    {
        $this->vorname = $vorname;

        return $this;
    }

    public function getGebdatum(): ?\DateTimeInterface
    {
        return $this->gebdatum;
    }

    public function setGebdatum(?\DateTimeInterface $gebdatum): static
    {
        $this->gebdatum = $gebdatum;

        return $this;
    }

    public function getLoeschung(): ?\DateTimeInterface
    {
        return $this->loeschung;
    }

    public function setLoeschung(?\DateTimeInterface $loeschung): static
    {
        $this->loeschung = $loeschung;

        return $this;
    }

    public function getErfassung(): ?\DateTimeInterface
    {
        return $this->erfassung;
    }

    public function setErfassung(?\DateTimeInterface $erfassung): static
    {
        $this->erfassung = $erfassung;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        // Hole alle Getter-Methoden der Entity
        $methods = get_class_methods($this);

        foreach ($methods as $method) {
            // Überprüfen, ob die Methode mit "get" beginnt (z.B. getName)
            if (strpos($method, 'get') === 0) {
                // Den Namen des Felds ohne "get" am Anfang (z.B. getName -> name)
                $fieldName = lcfirst(substr($method, 3));
                $data[$fieldName] = $this->$method(); // Füge das Ergebnis der Methode zum Array hinzu
            }
        }

        return $data;
    }

    public function getVorgangsnummer(): ?string
    {
        return $this->vorgangsnummer;
    }

    public function setVorgangsnummer(string $vorgangsnummer): static
    {
        $this->vorgangsnummer = $vorgangsnummer;

        return $this;
    }

}
