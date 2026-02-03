<?php

namespace App\Entity;

use App\Repository\AnimalTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalTypeRepository::class)]
class AnimalType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    /**
     * @var Collection<int, AnimalSubtype>
     */
    #[ORM\OneToMany(targetEntity: AnimalSubtype::class, mappedBy: 'animalType')]
    private Collection $subtypes;

    public function __construct()
    {
        $this->subtypes = new ArrayCollection();
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

    /**
     * @return Collection<int, AnimalSubtype>
     */
    public function getSubtypes(): Collection
    {
        return $this->subtypes;
    }

    public function addSubtype(AnimalSubtype $subtype): static
    {
        if (!$this->subtypes->contains($subtype)) {
            $this->subtypes->add($subtype);
            $subtype->setAnimalType($this);
        }

        return $this;
    }

    public function removeSubtype(AnimalSubtype $subtype): static
    {
        if ($this->subtypes->removeElement($subtype)) {
            // set the owning side to null (unless already changed)
            if ($subtype->getAnimalType() === $this) {
                $subtype->setAnimalType(null);
            }
        }

        return $this;
    }
}
