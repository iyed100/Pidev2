<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\HotelRepository;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ORM\Table(name: 'hotel')]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $adresse = null;

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $nombreEtoiles = null;

    public function getNombreEtoiles(): ?int
    {
        return $this->nombreEtoiles;
    }

    public function setNombreEtoiles(int $nombreEtoiles): self
    {
        $this->nombreEtoiles = $nombreEtoiles;
        return $this;
    }

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $prixParNuit;

    public function getPrixParNuit(): ?float
    {
        return $this->prixParNuit;
    }

    public function setPrixParNuit(float $prixParNuit): self
    {
        $this->prixParNuit = $prixParNuit;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $image = null;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: CoworkingSpace::class, mappedBy: 'hotel')]
    private Collection $coworkingSpaces;

    /**
     * @return Collection<int, CoworkingSpace>
     */
    public function getCoworkingSpaces(): Collection
    {
        if (!$this->coworkingSpaces instanceof Collection) {
            $this->coworkingSpaces = new ArrayCollection();
        }
        return $this->coworkingSpaces;
    }

    public function addCoworkingSpace(CoworkingSpace $coworkingSpace): self
    {
        if (!$this->getCoworkingSpaces()->contains($coworkingSpace)) {
            $this->getCoworkingSpaces()->add($coworkingSpace);
        }
        return $this;
    }

    public function removeCoworkingSpace(CoworkingSpace $coworkingSpace): self
    {
        $this->getCoworkingSpaces()->removeElement($coworkingSpace);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'hotel')]
    private Collection $reservations;

    public function __construct()
    {
        $this->coworkingSpaces = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        if (!$this->reservations instanceof Collection) {
            $this->reservations = new ArrayCollection();
        }
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->getReservations()->contains($reservation)) {
            $this->getReservations()->add($reservation);
        }
        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        $this->getReservations()->removeElement($reservation);
        return $this;
    }

}
