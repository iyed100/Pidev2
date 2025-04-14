<?php

namespace App\Entity;

use App\Repository\CoworkingSpaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoworkingSpaceRepository::class)]
class CoworkingSpace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter a name')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Name must be at least {{ limit }} characters long',
        maxMessage: 'Name cannot be longer than {{ limit }} characters'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter an address')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Address must be at least {{ limit }} characters long',
        maxMessage: 'Address cannot be longer than {{ limit }} characters'
    )]
    private ?string $adresse = null;

    #[ORM\Column(name: "prixParHeure")]
    #[Assert\NotBlank(message: 'Please enter the price per hour')]
    #[Assert\Positive(message: 'Price must be a positive number')]
    #[Assert\Range(
        min: 0.01,
        max: 1000,
        notInRangeMessage: 'Price per hour must be between {{ min }} and {{ max }} EUR'
    )]
    private ?float $prixParHeure = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: '2048k',
        mimeTypes: ['image/jpeg', 'image/png', 'image/gif'],
        mimeTypesMessage: 'Please upload a valid image (JPEG, PNG, GIF)'
    )]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'coworkingSpaces')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Please select a hotel')]
    private ?Hotel $hotel = null;

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

    public function getPrixParHeure(): ?float
    {
        return $this->prixParHeure;
    }

    public function setPrixParHeure(float $prixParHeure): static
    {
        $this->prixParHeure = $prixParHeure;
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

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): static
    {
        $this->hotel = $hotel;
        return $this;
    }
} 