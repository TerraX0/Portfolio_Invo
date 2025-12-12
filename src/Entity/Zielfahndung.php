<?php

namespace App\Entity;

use App\Repository\ZielfahndungRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: ZielfahndungRepository::class)]
#[UniqueEntity(fields: ['vorgangsnummer'], message: 'Ein Vorgang mit dieser Vorgangsnummer existiert bereits.')]
class Zielfahndung
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20,  unique: true)]
    private ?string $vorgangsnummer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sachbearbeiter1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sachbearbeiter2 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $festnahme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $einsendende_dst = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sachbearbeitende_dst = null;

    #[ORM\ManyToOne]
    private ?Bundesland $bundesland = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eingang = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $geschaeftszeichen = null;

    #[ORM\ManyToOne]
    private ?ZFKenner $zfkenner = null;

    #[ORM\ManyToOne]
    private ?Nationen $auslaendische_behoerde = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $erfassung = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $aussonderung = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $inhalt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $massnahmen = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bemerkung = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $wv = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $erledigt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSachbearbeiter1(): ?string
    {
        return $this->sachbearbeiter1;
    }

    public function setSachbearbeiter1(?string $sachbearbeiter1): static
    {
        $this->sachbearbeiter1 = $sachbearbeiter1;

        return $this;
    }

    public function getSachbearbeiter2(): ?string
    {
        return $this->sachbearbeiter2;
    }

    public function setSachbearbeiter2(?string $sachbearbeiter2): static
    {
        $this->sachbearbeiter2 = $sachbearbeiter2;

        return $this;
    }

    public function getFestnahme(): ?\DateTimeInterface
    {
        return $this->festnahme;
    }

    public function setFestnahme(?\DateTimeInterface $festnahme): static
    {
        $this->festnahme = $festnahme;

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

    public function getSachbearbeitendeDst(): ?string
    {
        return $this->sachbearbeitende_dst;
    }

    public function setSachbearbeitendeDst(?string $sachbearbeitende_dst): static
    {
        $this->sachbearbeitende_dst = $sachbearbeitende_dst;

        return $this;
    }

    public function getBundesland(): ?Bundesland
    {
        return $this->bundesland;
    }

    public function setBundesland(?Bundesland $bundesland): static
    {
        $this->bundesland = $bundesland;

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

    public function getZfkenner(): ?ZFKenner
    {
        return $this->zfkenner;
    }

    public function setZfkenner(?ZFKenner $zfkenner): static
    {
        $this->zfkenner = $zfkenner;

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

    public function getBemerkung(): ?string
    {
        return $this->bemerkung;
    }

    public function setBemerkung(?string $bemerkung): static
    {
        $this->bemerkung = $bemerkung;

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

    public function getErledigt(): ?\DateTimeInterface
    {
        return $this->erledigt;
    }

    public function setErledigt(?\DateTimeInterface $erledigt): static
    {
        $this->erledigt = $erledigt;

        return $this;
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
