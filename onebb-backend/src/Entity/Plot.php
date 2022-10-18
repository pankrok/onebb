<?php

namespace App\Entity;

use App\Repository\PlotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlotRepository::class)
* @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("slug")
 * @ApiResource(
        order={"updated_at": "DESC"},
 *      attributes={"security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"},
        normalizationContext={"groups": {"plot", "board"}},
 *      collectionOperations={
            "get"={
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
                "order"={"updated_at": "DESC"}
                }, 
            "post"={"security"="is_granted('ROLE_USER')",}
        },
 *      itemOperations={
 *          "get"=
            {
                "normalizationContext"={"groups": {"plot"}},
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
            },
 *           "put"={"security"="is_granted('ROLE_MODERATOR') or object.user in user"},
             "delete"={"security"="is_granted('ROLE_MODERATOR')"}
 *      },
        subresourceOperations={
            "api_plots_posts_get_subresource"={
                "method"="GET",
                "path":"/plots/{id}/posts",
                "normalization_context":{
                    "groups": {"plot_subresource"}                    
                }                  
            }            
        },
 *  )
 */
class Plot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"board", "plot"}) 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"board", "plot"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"board", "plot"})
     */
    private $tags = 'none';

    /**
     * @ORM\ManyToOne(targetEntity=Board::class, inversedBy="plots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $board;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @Groups({"board", "plot"})
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"board", "plot"})
     */
    private $active = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"board", "plot"})
     */
    private $pinned;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"board", "plot"})
     */
    private $pinned_order;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"board", "plot"})
     */
    private $locked = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"board", "plot"})
     */
    private $hidden = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"board", "plot"})
     */
    private $views;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"board", "plot"})
     */
    private $stars;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"board", "plot"})
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="plot", orphanRemoval=true)
     * @ApiSubresource()
     */
    private $posts;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"board", "plot"}) 
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"board"})
     */
    private $post_no;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @Groups({"board"})
     */
    private $last_active_user;

    /**
    * @Groups({"plot"}) 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_desc;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
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

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function setBoard(?Board $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getPinned(): ?bool
    {
        return $this->pinned;
    }

    public function setPinned(?bool $pinned): self
    {
        $this->pinned = $pinned;

        return $this;
    }

    public function getPinnedOrder(): ?int
    {
        return $this->pinned_order;
    }

    public function setPinnedOrder(?int $pinned_order): self
    {
        $this->pinned_order = $pinned_order;

        return $this;
    }

    public function getLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }
    
    public function incrementViews(): self
    {
        if (!is_int($this->views)) {
            $this->views = 0;
        }        
        
        $this->views++;
        return $this;
    }

    public function getStars(): ?float
    {
        return $this->stars;
    }

    public function setStars(?float $stars): self
    {
        $this->stars = $stars;

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
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setPlot($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getPlot() === $this) {
                $post->setPlot(null);
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

    public function getPostNo(): ?int
    {
        return $this->post_no;
    }

    public function setPostNo(?int $post_no): self
    {
        $this->post_no = $post_no;

        return $this;
    }
    
    public function incrementPostNo(): self
    {
        if (!is_int($this->post_no)) {
            $this->post_no = 0;
        }
        
        $this->post_no++;
        return $this;
    }

    public function getLastActiveUser(): ?User
    {
        return $this->last_active_user;
    }

    public function setLastActiveUser(?User $last_active_user): self
    {
        $this->last_active_user = $last_active_user;

        return $this;
    }

    public function getMetaDesc(): ?string
    {
        return $this->meta_desc;
    }

    public function setMetaDesc(?string $meta_desc): self
    {
        $this->meta_desc = $meta_desc;

        return $this;
    }
}
