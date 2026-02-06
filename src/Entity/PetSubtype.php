<?php

namespace App\Entity;

use App\Repository\PetSubtypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PetSubtypeRepository::class)]
class PetSubtype
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['petSubtype:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups(['petSubtype:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'subtypes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['petSubtype:read'])]
    private ?PetType $petType = null;

    /**
     * @var Collection<int, Pet>
     */
    #[ORM\OneToMany(targetEntity: Pet::class, mappedBy: 'subtype')]
    #[Groups(['petSubtype:read'])]
    private Collection $pets;

    public function __construct()
    {
        $this->pets = new ArrayCollection();
    }

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

    public function getPetType(): ?PetType
    {
        return $this->petType;
    }

    public function setPetType(?PetType $petType): static
    {
        $this->petType = $petType;

        return $this;
    }

    /**
     * @return Collection<int, Pet>
     */
    public function getPets(): Collection
    {
        return $this->pets;
    }

    public function addPet(Pet $pet): static
    {
        if (!$this->pets->contains($pet)) {
            $this->pets->add($pet);
            $pet->setSubtype($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): static
    {
        if ($this->pets->removeElement($pet)) {
            // set the owning side to null (unless already changed)
            if ($pet->getSubtype() === $this) {
                $pet->setSubtype(null);
            }
        }

        return $this;
    }
}
