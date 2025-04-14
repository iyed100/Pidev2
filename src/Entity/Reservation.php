<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idhotel = null;

    #[ORM\Column]
    private ?int $nbrnuit = null;

    #[ORM\Column]
    private ?int $idspace = null;

    #[ORM\Column]
    private ?int $nbrheure = null;

    #[ORM\Column]
    private ?int $idtransport = null;

    #[ORM\Column]
    private ?int $iduser = null;

    #[ORM\Column(length: 255)]
    private ?string $typeservice = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdhotel(): ?int
    {
        return $this->idhotel;
    }

    public function setIdhotel(int $idhotel): static
    {
        $this->idhotel = $idhotel;

        return $this;
    }

    public function getNbrnuit(): ?int
    {
        return $this->nbrnuit;
    }

    public function setNbrnuit(int $nbrnuit): static
    {
        $this->nbrnuit = $nbrnuit;

        return $this;
    }

    public function getIdspace(): ?int
    {
        return $this->idspace;
    }

    public function setIdspace(int $idspace): static
    {
        $this->idspace = $idspace;

        return $this;
    }

    public function getNbrheure(): ?int
    {
        return $this->nbrheure;
    }

    public function setNbrheure(int $nbrheure): static
    {
        $this->nbrheure = $nbrheure;

        return $this;
    }

    public function getIdtransport(): ?int
    {
        return $this->idtransport;
    }

    public function setIdtransport(int $idtransport): static
    {
        $this->idtransport = $idtransport;

        return $this;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getTypeservice(): ?string
    {
        return $this->typeservice;
    }

    public function setTypeservice(string $typeservice): static
    {
        $this->typeservice = $typeservice;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
