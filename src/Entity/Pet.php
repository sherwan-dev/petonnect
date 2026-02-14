<?php

namespace App\Entity;

use App\Repository\PetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, PetFollow>
     */
    #[ORM\OneToMany(targetEntity: PetFollow::class, mappedBy: 'follower')]
    private Collection $petFollower;

    /**
     * @var Collection<int, PetFollow>
     */
    #[ORM\OneToMany(targetEntity: PetFollow::class, mappedBy: 'followed')]
    private Collection $petFollowed;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author', orphanRemoval: true)]
    private Collection $posts;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'author', orphanRemoval: true)]
    private Collection $comments;

    /**
     * @var Collection<int, PostLike>
     */
    #[ORM\OneToMany(targetEntity: PostLike::class, mappedBy: 'pet', orphanRemoval: true)]
    private Collection $likedPosts;

    public function __construct()
    {
        $this->petFollower = new ArrayCollection();
        $this->petFollowed = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likedPosts = new ArrayCollection();
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

    /**
     * @return Collection<int, PetFollow>
     */
    public function getPetFollower(): Collection
    {
        return $this->petFollower;
    }

    public function addPetFollower(PetFollow $petFollower): static
    {
        if (!$this->petFollower->contains($petFollower)) {
            $this->petFollower->add($petFollower);
            $petFollower->setFollower($this);
        }

        return $this;
    }

    public function removePetFollower(PetFollow $petFollower): static
    {
        if ($this->petFollower->removeElement($petFollower)) {
            // set the owning side to null (unless already changed)
            if ($petFollower->getFollower() === $this) {
                $petFollower->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PetFollow>
     */
    public function getPetFollowed(): Collection
    {
        return $this->petFollowed;
    }

    public function addPetFollowed(PetFollow $petFollowed): static
    {
        if (!$this->petFollowed->contains($petFollowed)) {
            $this->petFollowed->add($petFollowed);
            $petFollowed->setFollowed($this);
        }

        return $this;
    }

    public function removePetFollowed(PetFollow $petFollowed): static
    {
        if ($this->petFollowed->removeElement($petFollowed)) {
            // set the owning side to null (unless already changed)
            if ($petFollowed->getFollowed() === $this) {
                $petFollowed->setFollowed(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PostLike>
     */
    public function getLikedPosts(): Collection
    {
        return $this->likedPosts;
    }

    public function addLikedPost(PostLike $likedPost): static
    {
        if (!$this->likedPosts->contains($likedPost)) {
            $this->likedPosts->add($likedPost);
            $likedPost->setPet($this);
        }

        return $this;
    }

    public function removeLikedPost(PostLike $likedPost): static
    {
        if ($this->likedPosts->removeElement($likedPost)) {
            // set the owning side to null (unless already changed)
            if ($likedPost->getPet() === $this) {
                $likedPost->setPet(null);
            }
        }

        return $this;
    }

}
