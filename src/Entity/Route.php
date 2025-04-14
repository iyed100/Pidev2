<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter a departure location')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Departure location must be at least {{ limit }} characters long',
        maxMessage: 'Departure location cannot be longer than {{ limit }} characters'
    )]
    private ?string $depart = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter an arrival location')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Arrival location must be at least {{ limit }} characters long',
        maxMessage: 'Arrival location cannot be longer than {{ limit }} characters'
    )]
    private ?string $arrivee = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please enter the distance')]
    #[Assert\Positive(message: 'Distance must be a positive number')]
    #[Assert\Range(
        min: 0.1,
        max: 20000,
        notInRangeMessage: 'Distance must be between {{ min }} and {{ max }} km'
    )]
    private ?float $distance = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Please enter the duration')]
    #[Assert\Regex(
        pattern: '/^([0-9]+[hH])?([0-9]+[mM])?$/',
        message: 'Duration must be in format like "2h30m" or "45m"'
    )]
    private ?string $duree = null;
  
    #[ORM\ManyToOne(inversedBy: 'routes')]
    #[ORM\JoinColumn(name: 'transportId', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotNull(message: 'Please select a transport mean')]  
    
    private ?TransportMeans $transport = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): static
    {
        $this->depart = $depart;
        return $this;
    }

    public function getArrivee(): ?string
    {
        return $this->arrivee;
    }

    public function setArrivee(string $arrivee): static
    {
        $this->arrivee = $arrivee;
        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): static
    {
        $this->distance = $distance;
        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;
        return $this;
    }

    public function getTransport(): ?TransportMeans
    {
        return $this->transport;
    }

    public function setTransport(?TransportMeans $transport): static
    {
        $this->transport = $transport;
        return $this;
    }
} 