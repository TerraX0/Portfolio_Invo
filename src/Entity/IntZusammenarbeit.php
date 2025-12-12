<?php

namespace App\Entity;

use App\Repository\IntZusammenarbeitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: IntZusammenarbeitRepository::class)]
#[UniqueEntity(fields: ['vorgangsnummer'], message: 'Ein Vorgang mit dieser Vorgangsnummer existiert bereits.')]
class IntZusammenarbeit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $vorgangsnummer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $vorgangsdatum = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $einsendende_dst = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eingang = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $geschaeftszeichen = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $erfassung = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $aussonderung = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vorname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $gebdatum = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $inhalt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $massnahmen = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bearbeitung = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $hinweise = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $wv = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $erledigung = null;

    #[ORM\Column(nullable: true)]
    private ?bool $reminder = null;

    #[ORM\ManyToOne]
    private ?IPKenner $ipkenner = null;

    #[ORM\ManyToOne]
    private ?Nationen $auslaendische_behoerde = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVorgangsdatum(): ?\DateTimeInterface
    {
        return $this->vorgangsdatum;
    }

    public function setVorgangsdatum(?\DateTimeInterface $vorgangsdatum): static
    {
        $this->vorgangsdatum = $vorgangsdatum;

        return $this;
    }

    public function getEinsendendeDst(): ?string
    {
        return $this->einsendende_dst;
    }

    public function setEinsendendeDst(?string $einsendende_dst): static
    {
        $this->einsendende_dst = $einsendende_dst;

        return $this;
    }

    public function getEingang(): ?string
    {
        return $this->eingang;
    }

    public function setEingang(?string $eingang): static
    {
        $this->eingang = $eingang;

        return $this;
    }

    public function getGeschaeftszeichen(): ?string
    {
        return $this->geschaeftszeichen;
    }

    public function setGeschaeftszeichen(?string $geschaeftszeichen): static
    {
        $this->geschaeftszeichen = $geschaeftszeichen;

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

    public function getAussonderung(): ?\DateTimeInterface
    {
        return $this->aussonderung;
    }

    public function setAussonderung(?\DateTimeInterface $aussonderung): static
    {
        $this->aussonderung = $aussonderung;

        return $this;
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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getInhalt(): ?string
    {
        return $this->inhalt;
    }

    public function setInhalt(?string $inhalt): static
    {
        $this->inhalt = $inhalt;

        return $this;
    }

    public function getMassnahmen(): ?string
    {
        return $this->massnahmen;
    }

    public function setMassnahmen(?string $massnahmen): static
    {
        $this->massnahmen = $massnahmen;

        return $this;
    }

    public function isBearbeitung(): ?bool
    {
        return $this->bearbeitung;
    }

    public function setBearbeitung(?bool $bearbeitung): static
    {
        $this->bearbeitung = $bearbeitung;

        return $this;
    }

    public function getHinweise(): ?string
    {
        return $this->hinweise;
    }

    public function setHinweise(?string $hinweise): static
    {
        $this->hinweise = $hinweise;

        return $this;
    }

    public function getWv(): ?\DateTimeInterface
    {
        return $this->wv;
    }

    public function setWv(?\DateTimeInterface $wv): static
    {
        $this->wv = $wv;

        return $this;
    }

    public function getErledigung(): ?\DateTimeInterface
    {
        return $this->erledigung;
    }

    public function setErledigung(?\DateTimeInterface $erledigung): static
    {
        $this->erledigung = $erledigung;

        return $this;
    }

    public function isReminder(): ?bool
    {
        return $this->reminder;
    }

    public function setReminder(?bool $reminder): static
    {
        $this->reminder = $reminder;

        return $this;
    }

    public function getIpkenner(): ?IPKenner
    {
        return $this->ipkenner;
    }

    public function setIpkenner(?IPKenner $ipkenner): static
    {
        $this->ipkenner = $ipkenner;

        return $this;
    }

    public function getAuslaendischeBehoerde(): ?Nationen
    {
        return $this->auslaendische_behoerde;
    }

    public function setAuslaendischeBehoerde(?Nationen $auslaendische_behoerde): static
    {
        $this->auslaendische_behoerde = $auslaendische_behoerde;

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
