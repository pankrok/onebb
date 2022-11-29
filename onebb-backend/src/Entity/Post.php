<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ApiResource(
        subresourceOperations={
            "api_plots_posts_get_subresource"={
                "method"="GET",
                "normalization_context":{
                    "groups": {"plot_subresource"}                    
                }                
            }            
        },
 
 *      collectionOperations={
            "get"={
                "normalization_context"={"groups": {"plot_subresource"}},
                "security"="is_granted('ROLE_POST_READ')"
                }, 
            "post"={
                "normalization_context"={"groups": {"plot_subresource", "plot:write"}},
                "security"="is_granted('ROLE_POST_CREATE')",
             }
        },
 *      itemOperations={
 *           "get"={
                "normalization_context"={"groups": {"plot_subresource"}},
                "security"="is_granted('ROLE_POST_READ')"
                },
 *           "put"={"security"="is_granted('ROLE_POST_EDIT') or object.user == user"},
             "delete"={"security"="is_granted('ROLE_PLOT_DELETE')"}
 *           }
 *  )
 *  @ApiFilter(OrderFilter::class, properties={"created_at"}, arguments={"orderParameterName": "order"})
 *  @ApiFilter(SearchFilter::class, properties={"content": "partial"}) 
 */


class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"plot_subresource"}) 
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @Groups({"plot_subresource", "user"}) 
     * @ApiFilter(SearchFilter::class, properties={"user.username": "partial"}) 
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Plot::class, inversedBy="posts")
     * @Groups({"plot:write"}) 
     * @ORM\JoinColumn(nullable=false)
     * @ApiFilter(SearchFilter::class, properties={"plot.name": "partial"}) 
     */
    private $plot;

    /**
     * @ORM\Column(type="text")
     * @Groups({"plot_subresource"}) 
     */
    private $content;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"plot_subresource"}) 
     */
    private $reputation;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"plot_subresource"}) 
     */
    private $hidden;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"plot_subresource"}) 
     */
    private $edit_by;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"plot_subresource"}) 
     */
    private $created_at;

    public function __construct()
    {
        $this->edit_by = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPlot(): ?Plot
    {
        return $this->plot;
    }

    public function setPlot(?Plot $plot): self
    {
        $this->plot = $plot;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getReputation(): ?int
    {
        return $this->reputation;
    }

    public function setReputation(?int $reputation): self
    {
        $this->reputation = $reputation;

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

    /**
     * @return Collection|User[]
     */
    public function getEditBy(): Collection
    {
        return $this->edit_by;
    }

    public function addEditBy(User $editBy): self
    {
        if (!$this->edit_by->contains($editBy)) {
            $this->edit_by[] = $editBy;
        }

        return $this;
    }

    public function removeEditBy(User $editBy): self
    {
        $this->edit_by->removeElement($editBy);

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
}
