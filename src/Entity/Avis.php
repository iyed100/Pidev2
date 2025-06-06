<?php
// src/Entity/Avis.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\AvisRepository")]
#[ORM\Table(name: "avis")]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "integer", name: "userId",nullable: true)]
    private $userId;

    #[ORM\Column(type: "integer", name: "serviceId")]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: "Service ID cannot be null")]
    private int $serviceId;

    #[ORM\Column(type: "integer", nullable: true)]
    private $note;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotNull(message: "Comment cannot be null")]
    #[Assert\NotBlank(message: "Comment cannot be empty")]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: "Comment must be at least {{ limit }} characters long",
        maxMessage: "Comment cannot be longer than {{ limit }} characters"
    )]
    private string $comment;

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

    public function getServiceId(): ?int
    {
        return $this->serviceId;
    }

    public function setServiceId(int $serviceId): self
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }
    public function isEmpty(): bool
{
    return empty($this->note) && empty($this->comment);
}
}