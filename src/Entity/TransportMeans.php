<?php

namespace App\Entity;

use App\Repository\TransportMeansRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransportMeansRepository::class)]
class TransportMeans
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

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Please select a transport type')]
    #[Assert\Choice(
        choices: ['bus', 'train', 'metro', 'taxi', 'bicycle'],
        message: 'Choose a valid transport type'
    )]
    private ?string $type = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please enter the capacity')]
    #[Assert\Positive(message: 'Capacity must be a positive number')]
    #[Assert\Range(
        min: 1,
        max: 1000,
        notInRangeMessage: 'Capacity must be between {{ min }} and {{ max }}'
    )]
    private ?int $capacite = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please enter the price')]
    #[Assert\Positive(message: 'Price must be a positive number')]
    #[Assert\Range(
        min: 0.01,
        max: 10000,
        notInRangeMessage: 'Price must be between {{ min }} and {{ max }} EUR'
    )]
    private ?float $prix = null;

    #[ORM\Column(name: 'dateDepart', type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Please enter a departure date')]
    #[Assert\Type("\DateTimeInterface")]
    #[Assert\GreaterThanOrEqual(
        value: 'today',
        message: 'The departure date must be today or in the future'
    )]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: '2048k',
        mimeTypes: ['image/jpeg', 'image/png', 'image/gif'],
        mimeTypesMessage: 'Please upload a valid image (JPEG, PNG, GIF)'
    )]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'transport', targetEntity: Route::class, orphanRemoval: true)]
    private Collection $routes;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): static
    {
        $this->dateDepart = $dateDepart;
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
     * @return Collection<int, Route>
     */
    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function addRoute(Route $route): static
    {
        if (!$this->routes->contains($route)) {
            $this->routes->add($route);
            $route->setTransport($this);
        }
        return $this;
    }

    public function removeRoute(Route $route): static
    {
        if ($this->routes->removeElement($route)) {
            if ($route->getTransport() === $this) {
                $route->setTransport(null);
            }
        }
        return $this;
    }
} 