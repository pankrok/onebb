<?php

namespace App\Entity;

use App\Repository\BoxRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=BoxRepository::class)
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
             "delete"={"security"="is_granted('ROLE_SKIN_DELETE')"}
 *           }
 *  )
 */
class Box
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"skin"}) 
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"skin"}) 
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=16)
     * @Groups({"skin"}) 
     */
    private $engine;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"skin"}) 
     */
    private $html;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

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

    public function getEngine(): ?string
    {
        return $this->engine;
    }

    public function setEngine(string $engine): self
    {
        $this->engine = $engine;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): self
    {
        $this->html = $html;

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
