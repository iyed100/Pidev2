<?php
// src/Entity/Claim.php
namespace App\Entity;

use App\Repository\ClaimRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ClaimRepository")]
#[ORM\Table(name: "claim")]
class Claim
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(name: "userId", type: "integer", nullable: true)]
private int $userId;

    #[ORM\Column(type: "string", length: 255, options: ["default" => "En attente"])]
    private $status = 'En attente';

    #[ORM\Column(type: "text")]
    private $description;

    #[ORM\Column(type: "date", name: "Cdate")]
    private $cdate;

    #[ORM\OneToMany(mappedBy: 'Claim', targetEntity: Response::class)]
    private Collection $responses;

    

    public function __construct()
    {
        $this->responses = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getCdate(): ?\DateTimeInterface
    {
        return $this->cdate;
    }

    public function setCdate(\DateTimeInterface $cdate): self
    {
        $this->cdate = $cdate;
        return $this;
    }

    /**
     * @return Collection<int, Response>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setClaim($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): static
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getClaim() === $this) {
                $response->setClaim(null);
            }
        }

        return $this;
    }
    public function __toString(): string
{
    return sprintf(
        'Claim #%d - %s (%s)',
        $this->id,
        substr($this->description, 0, 30) . (strlen($this->description) > 30 ? '...' : ''),
        $this->cdate->format('Y-m-d')
    );
}

public function isEmpty(): bool
{
    return empty($this->description);
}
}