<?php

namespace App\Entity;

use App\Repository\SkinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ExistsFilter;

/**
 * @ORM\Entity(repositoryClass=SkinRepository::class)
 * @ApiResource(
 *      collectionOperations={
            "get"={
                "normalization_context"={"groups": {"skin"}},
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"
                }, 
            "post"={
                "normalization_context"={"groups": {"skin"}},
                "security"="is_granted('ROLE_SKIN_CREATE')",
             }
        },
 *      itemOperations={
 *           "get"={
                "normalization_context"={"groups": {"skin"}},
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"
                },
 *           "put"={"security"="is_granted('ROLE_SKIN_EDIT')"},
             "delete"={"security"="is_granted('ROLE_SKINT_DELETE')"}
 *           }
 *  )
 */
class Skin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"skin_cfg"}) 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"skin_cfg"}) 
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=8)
     * @Groups({"skin_cfg"}) 
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Groups({"skin_cfg"}) 
     */
    private $author;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"skin_cfg"}) 
     * @ApiFilter(ExistsFilter::class)
     */
    private $active;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"skin_cfg"}) 
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"skin_cfg"}) 
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=SkinBoxes::class, mappedBy="skin", orphanRemoval=true)
     * @Groups({"skin"}) 
     */
    private $skinBoxes;

    public function __construct()
    {
        $this->skinBoxes = new ArrayCollection();
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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
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
     * @return Collection<int, SkinBoxes>
     */
    public function getSkinBoxes(): Collection
    {
        return $this->skinBoxes;
    }

    public function addSkinBox(SkinBoxes $skinBox): self
    {
        if (!$this->skinBoxes->contains($skinBox)) {
            $this->skinBoxes[] = $skinBox;
            $skinBox->setSkin($this);
        }

        return $this;
    }

    public function removeSkinBox(SkinBoxes $skinBox): self
    {
        if ($this->skinBoxes->removeElement($skinBox)) {
            // set the owning side to null (unless already changed)
            if ($skinBox->getSkin() === $this) {
                $skinBox->setSkin(null);
            }
        }

        return $this;
    }
}
