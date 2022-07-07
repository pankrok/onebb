<?php

namespace App\Entity;

use App\Repository\MetafieldValueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MetafieldValueRepository::class)
 */
class MetafieldValue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Metafield::class, inversedBy="metafieldValues")
     */
    private $metafield;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $value = [];


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetafield(): ?Metafield
    {
        return $this->metafield;
    }

    public function setMetafield(?Metafield $metafield): self
    {
        $this->metafield = $metafield;

        return $this;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(?array $value): self
    {
        $this->value = $value;

        return $this;
    }
}
