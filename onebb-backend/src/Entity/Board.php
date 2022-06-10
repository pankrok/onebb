<?php

namespace App\Entity;

use App\Repository\BoardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=BoardRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("slug")

 * @ApiResource(
        order={"updated_at": "ASC"},
       normalizationContext={"groups": {"board"}},
 *      collectionOperations={
 *          "get" = {"normalization_context"={"groups": {"category"}}, "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"},
 *          "post" = {"normalization_context"={"groups": {"board"}}, "security"="is_granted('ROLE_BOARD_CREATE')"}
 *      },
 *      itemOperations={
 *         "get" = {"normalization_context"={"groups": {"board"}}, "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"},
 *         "put"={"security_post_denormalize"="is_granted('ROLE_BOARD_EDIT')"},
 *         "delete"={"security_post_denormalize"="is_granted('ROLE_BOARD_DELETE')"}
 *     }
 *  )
 */
 
class Board
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"category", "board"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"category", "board"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"category", "board"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="boards")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"board"})
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Board::class, inversedBy="boards")
     * @Groups({"category", "board"})
     */
    private $parent;

    /**
     * @ORM\ManyToMany(targetEntity=Board::class, mappedBy="parent")
     */
    private $boards;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"category", "board"})
     */
    private $active;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"category", "board"})
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Plot::class, mappedBy="board", orphanRemoval=true)
     * @ORM\OrderBy({"updated_at" = "DESC"})
     * @Groups({"board"})
     
     */
     
    private $plots;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"category", "board"})
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"category", "board"})
     */
    private $priority;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"category", "board"})
     */
    private $plots_no;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"category", "board"})
     */
    private $posts_no;
    
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @Groups({"category", "board"})
     */
    private $last_active_user;

    public function __construct()
    {
        $this->parent = new ArrayCollection();
        $this->boards = new ArrayCollection();
        $this->plots = new ArrayCollection();
    }
    
    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getParent(): Collection
    {
        return $this->parent;
    }

    public function addParent(self $parent): self
    {
        if (!$this->parent->contains($parent)) {
            $this->parent[] = $parent;
        }

        return $this;
    }

    public function removeParent(self $parent): self
    {
        $this->parent->removeElement($parent);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getBoards(): Collection
    {
        return $this->boards;
    }

    public function addBoard(self $board): self
    {
        if (!$this->boards->contains($board)) {
            $this->boards[] = $board;
            $board->addParent($this);
        }

        return $this;
    }

    public function removeBoard(self $board): self
    {
        if ($this->boards->removeElement($board)) {
            $board->removeParent($this);
        }

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|Plot[]
     */
    public function getPlots(): Collection
    {
        return $this->plots;
    }

    public function addPlot(Plot $plot): self
    {
        if (!$this->plots->contains($plot)) {
            $this->plots[] = $plot;
            $plot->setBoard($this);
        }

        return $this;
    }

    public function removePlot(Plot $plot): self
    {
        if ($this->plots->removeElement($plot)) {
            // set the owning side to null (unless already changed)
            if ($plot->getBoard() === $this) {
                $plot->setBoard(null);
            }
        }

        return $this;
    }
    
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
    
    public function computeSlug(SluggerInterface $slugger)
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = (string) $slugger->slug((string) $this)->lower();
        }
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getPlotsNo(): ?int
    {
        return $this->plots_no;
    }

    public function setPlotsNo(?int $plots_no): self
    {
        $this->plots_no = $plots_no;

        return $this;
    }
    
    public function incrementPlotsNo(): self
    {
        if (!is_int($this->plots_no)) {
            $this->plots_no = 0;
        }
        
        $this->plots_no++;
        return $this;
    }

    public function getPostsNo(): ?int
    {
        return $this->posts_no;
    }

    public function setPostsNo(?int $posts_no): self
    {
        $this->posts_no = $posts_no;

        return $this;
    }
    
    public function incrementPostsNo(): self
    {
        if (!is_int($this->posts_no)) {
            $this->posts_no = 0;
        }
        
        $this->posts_no++;
        return $this;
    }
    
    public function getLastActiveUser(): ?User
    {
        return $this->last_active_user;
    }

    public function setLastActiveUser(?User $user): self
    {
        $this->last_active_user = $user;

        return $this;
    }
}
