<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\RouteRepository;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
#[ORM\Table(name: 'route')]
class Route
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
    private ?string $depart = null;

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): self
    {
        $this->depart = $depart;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $arrivee = null;

    public function getArrivee(): ?string
    {
        return $this->arrivee;
    }

    public function setArrivee(string $arrivee): self
    {
        $this->arrivee = $arrivee;
        return $this;
    }

    #[ORM\Column(type: 'decimal', precision: 12, scale: 3)]
    private string $distance;

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $duree = null;

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: TransportMean::class, inversedBy: 'routes')]
    #[ORM\JoinColumn(name: 'transportId', referencedColumnName: 'id')]
    private ?TransportMean $transportMean = null;

    public function getTransportMean(): ?TransportMean
    {
        return $this->transportMean;
    }

    public function setTransportMean(?TransportMean $transportMean): self
    {
        $this->transportMean = $transportMean;
        return $this;
    }

}
