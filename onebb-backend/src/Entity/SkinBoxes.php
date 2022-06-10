<?php

namespace App\Entity;

use App\Repository\SkinBoxesRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\SkinBoxesController;

/**
 * @ORM\Entity(repositoryClass=SkinBoxesRepository::class)
  * @ApiResource(
 *      collectionOperations={
            "get"={
                "normalization_context"={"groups": {"skin"}},
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"
                }, 
            "post"={
                "normalization_context"={"groups": {"skin"}},
                "security"="is_granted('ROLE_SKIN_CREATE')",
             },
        },
 *      itemOperations={
 *           "get"={
                "normalization_context"={"groups": {"skin"}},
                "security"="is_granted('ROLE_POST_READ')"
                },
 *           "put"={"security"="is_granted('ROLE_SKIN_EDIT')"},
             "delete"={"security"="is_granted('ROLE_SKINT_DELETE')"}
 *           }
 *  )
 */
class SkinBoxes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"skin"}) 
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Skin::class, inversedBy="skinBoxes")
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     * @ORM\JoinColumn(nullable=false)
     */
    private $skin;

    /**
     * @ORM\ManyToOne(targetEntity=Box::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"skin"}) 
     */
    private $box;

    /**
     * @ORM\Column(type="string", length=16)
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     * @Groups({"skin"}) 
     */
    private $page;

    /**
     * @ORM\Column(type="string", length=16)
     * @Groups({"skin"}) 
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     * @Groups({"skin"}) 
     */
    private $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkin(): ?Skin
    {
        return $this->skin;
    }

    public function setSkin(?Skin $skin): self
    {
        $this->skin = $skin;

        return $this;
    }

    public function getBox(): ?Box
    {
        return $this->box;
    }

    public function setBox(?Box $box): self
    {
        $this->box = $box;

        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

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
