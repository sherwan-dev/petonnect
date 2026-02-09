<?php

namespace App\Entity;

use App\Repository\PetRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\PetGender;

#[ORM\Entity(repositoryClass: PetRepository::class)]
class Pet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;
 
    #[ORM\Column(enumType: PetGender::class)]
    private ?PetGender $gender = null;

    #[ORM\ManyToOne(inversedBy: 'pets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'pets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PetType $type = null;

    #[ORM\ManyToOne(inversedBy: 'pets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PetSubtype $subtype = null;

    #[ORM\Column(type: 'string')]
    private string $profilePictureFileName;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getGender(): ?PetGender
    {
        return $this->gender;
    }

    public function setGender(?PetGender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getType(): ?PetType
    {
        return $this->type;
    }

    public function setType(?PetType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSubtype(): ?PetSubtype
    {
        return $this->subtype;
    }

    public function setSubtype(?PetSubtype $subtype): static
    {
        $this->subtype = $subtype;

        return $this;
    }

        public function getProfilePictureFileName(): string
    {
        return $this->profilePictureFileName;
    }

    public function setProfilePictureFileName(string $profilePictureFileName): self
    {
        $this->profilePictureFileName = $profilePictureFileName;

        return $this;
    }

}
