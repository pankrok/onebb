<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MetafieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MetafieldRepository::class)
 */
class Metafield
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $namespace;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $metafield_key;

    /**
     * @ORM\OneToMany(targetEntity=MetafieldValue::class, mappedBy="metafield")
     */
    private $metafieldValues;

    /**
     * @ORM\ManyToOne(targetEntity=Plugin::class, inversedBy="metafields")
     */
    private $plugin;

    public function __construct()
    {
        $this->metafieldValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getMetafieldKey(): ?string
    {
        return $this->metafield_key;
    }

    public function setMetafieldKey(string $metafield_key): self
    {
        $this->metafield_key = $metafield_key;

        return $this;
    }

    /**
     * @return Collection<int, MetafieldValue>
     */
    public function getMetafieldValues(): Collection
    {
        return $this->metafieldValues;
    }

    public function addMetafieldValue(MetafieldValue $metafieldValue): self
    {
        if (!$this->metafieldValues->contains($metafieldValue)) {
            $this->metafieldValues[] = $metafieldValue;
            $metafieldValue->setMetafield($this);
        }

        return $this;
    }

    public function removeMetafieldValue(MetafieldValue $metafieldValue): self
    {
        if ($this->metafieldValues->removeElement($metafieldValue)) {
            // set the owning side to null (unless already changed)
            if ($metafieldValue->getMetafield() === $this) {
                $metafieldValue->setMetafield(null);
            }
        }

        return $this;
    }

    public function getPlugin(): ?Plugin
    {
        return $this->plugin;
    }

    public function setPlugin(?Plugin $plugin): self
    {
        $this->plugin = $plugin;

        return $this;
    }
}
