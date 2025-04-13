<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReservationRepository;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'reservation')]
class Reservation
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

    #[ORM\ManyToOne(targetEntity: Hotel::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'idhotel', referencedColumnName: 'id')]
    private ?Hotel $hotel = null;

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $nbrnuit = null;

    public function getNbrnuit(): ?int
    {
        return $this->nbrnuit;
    }

    public function setNbrnuit(int $nbrnuit): self
    {
        $this->nbrnuit = $nbrnuit;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: CoworkingSpace::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'idspace', referencedColumnName: 'id')]
    private ?CoworkingSpace $coworkingSpace = null;

    public function getCoworkingSpace(): ?CoworkingSpace
    {
        return $this->coworkingSpace;
    }

    public function setCoworkingSpace(?CoworkingSpace $coworkingSpace): self
    {
        $this->coworkingSpace = $coworkingSpace;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $nbrheure = null;

    public function getNbrheure(): ?int
    {
        return $this->nbrheure;
    }

    public function setNbrheure(int $nbrheure): self
    {
        $this->nbrheure = $nbrheure;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: TransportMean::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'idtransport', referencedColumnName: 'id')]
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

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'iduser', referencedColumnName: 'id')]
    private ?Utilisateur $utilisateur = null;

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $typeservice = null;

    public function getTypeservice(): ?string
    {
        return $this->typeservice;
    }

    public function setTypeservice(?string $typeservice): self
    {
        $this->typeservice = $typeservice;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $statut = null;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Assurance::class, mappedBy: 'reservation')]
    private Collection $assurances;

    public function __construct()
    {
        $this->assurances = new ArrayCollection();
    }

    /**
     * @return Collection<int, Assurance>
     */
    public function getAssurances(): Collection
    {
        if (!$this->assurances instanceof Collection) {
            $this->assurances = new ArrayCollection();
        }
        return $this->assurances;
    }

    public function addAssurance(Assurance $assurance): self
    {
        if (!$this->getAssurances()->contains($assurance)) {
            $this->getAssurances()->add($assurance);
        }
        return $this;
    }

    public function removeAssurance(Assurance $assurance): self
    {
        $this->getAssurances()->removeElement($assurance);
        return $this;
    }

}
