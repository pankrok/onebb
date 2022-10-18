<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("slug")
 * @ApiResource(
 *      order={"priority": "ASC", "created_at": "ASC"},
 *      normalizationContext={"groups": {"category"}},
 *      collectionOperations={
 *          "get" = {"security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"},
 *          "post" = {"security"="is_granted('ROLE_CATEGORY_CREATE')"}
 *      },
 *      itemOperations={
 *         "get" = {"security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"},
 *         "put"={
                "security_post_denormalize"="is_granted('ROLE_CATEGORY_EDIT')"
            },
 *         "delete"={"security_post_denormalize"="is_granted('ROLE_CATEGORY_DELETE')"}
 *     }
 *)
 */
 #[ApiFilter(SearchFilter::class, properties: ['category' => 'active'])]
         
         class Category
         {
             /**
              * @ORM\Id
              * @ORM\GeneratedValue
              * @ORM\Column(type="integer")
              * @Groups({"category"})
              */
             private $id;
         
             /**
              * @ORM\Column(type="string", length=255)
              * @Groups({"category"})
              */
             private $name;
         
             /**
              * @ORM\Column(type="boolean", nullable=true)
              * @Groups({"category"})
              */
         
             private $active;
         
             /**
              * @ORM\Column(type="datetime_immutable")
              */
             private $updated_at;
         
             /**
              * @ORM\Column(type="datetime_immutable")
              */
             private $created_at;
         
             /**
              * @ORM\OneToMany(targetEntity=Board::class, mappedBy="category", orphanRemoval=true)
              * @Groups({"category"})
              */
             private $boards;
         
             /**
              * @ORM\Column(type="string", length=255)
              * @Groups({"category"})
              */
             private $slug;
         
             /**
              * @ORM\Column(type="integer", nullable=true)
              * @Groups({"category"})
              */
             private $priority;
      
             /**
             * @Groups({"category"})
              * @ORM\Column(type="string", length=255, nullable=true)
              */
             private $meta_desc;
         
             public function __toString(): string
             {
                 return $this->name;
             }
         
             public function __construct()
             {
                 $this->boards = new ArrayCollection();
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
              * @return Collection|Board[]
              */
             public function getBoards(): Collection
             {
                 return $this->boards;
             }
         
             public function addBoard(Board $board): self
             {
                 if (!$this->boards->contains($board)) {
                     $this->boards[] = $board;
                     $board->setCategory($this);
                 }
         
                 return $this;
             }
         
             public function removeBoard(Board $board): self
             {
                 if ($this->boards->removeElement($board)) {
                     // set the owning side to null (unless already changed)
                     if ($board->getCategory() === $this) {
                         $board->setCategory(null);
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
