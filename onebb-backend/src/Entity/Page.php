<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PageRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 * @ApiResource(
 *       order={"priority": "DESC", "updated_at": "DESC"},
 *      collectionOperations={
 *           "get"={"groups": {"pageList"}},
 *           "post"={"security"="is_granted('ROLE_PAGE_CREATE')"}
 *       },
 *      itemOperations={
 *           "get"={"groups": {"page"}}, 
 *           "delete"={"security"="is_granted('ROLE_PAGE_DELETE')"},
 *           "put"={"security"="is_granted('ROLE_PAGE_EDIT')"}
 *       }
 *  )   
 *)
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"pageList", "page"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"pageList", "page"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"page"})
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"pageList"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"pageList" , "page"})
     */
    private $updated_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"pageList", "page"})
     */
    private $priority;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"pageList" , "page"})
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     */
    private $active;
    
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

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

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

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
}
