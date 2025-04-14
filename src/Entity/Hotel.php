<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(name: "nombreEtoiles", type: "integer")]
        private ?int $nombreEtoiles = null;

        #[ORM\Column(name: 'prixParNuit', type: 'float')]
    private ?float $prixParNuit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: CoworkingSpace::class, orphanRemoval: true)]
    private Collection $coworkingSpaces;

    public function __construct()
    {
        $this->coworkingSpaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getNombreEtoiles(): ?int
    {
        return $this->nombreEtoiles;
    }

    public function setNombreEtoiles(int $nombreEtoiles): static
    {
        $this->nombreEtoiles = $nombreEtoiles;
        return $this;
    }

    public function getPrixParNuit(): ?float
    {
        return $this->prixParNuit;
    }

    public function setPrixParNuit(float $prixParNuit): static
    {
        $this->prixParNuit = $prixParNuit;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Collection<int, CoworkingSpace>
     */
    public function getCoworkingSpaces(): Collection
    {
        return $this->coworkingSpaces;
    }

    public function addCoworkingSpace(CoworkingSpace $coworkingSpace): static
    {
        if (!$this->coworkingSpaces->contains($coworkingSpace)) {
            $this->coworkingSpaces->add($coworkingSpace);
            $coworkingSpace->setHotel($this);
        }
        return $this;
    }

    public function removeCoworkingSpace(CoworkingSpace $coworkingSpace): static
    {
        if ($this->coworkingSpaces->removeElement($coworkingSpace)) {
            if ($coworkingSpace->getHotel() === $this) {
                $coworkingSpace->setHotel(null);
            }
        }
        return $this;
    }
} 