<?php

namespace App\Entity;

use App\Repository\PetFollowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PetFollowRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_FOLLOW_PAIR', columns: ['follower_id', 'followed_id'])]
class PetFollow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'petFollower')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Pet $follower = null;

    #[ORM\ManyToOne(inversedBy: 'petFollowed')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Pet $followed = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollower(): ?Pet
    {
        return $this->follower;
    }

    public function setFollower(?Pet $follower): static
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowed(): ?Pet
    {
        return $this->followed;
    }

    public function setFollowed(?Pet $followed): static
    {
        $this->followed = $followed;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

}
