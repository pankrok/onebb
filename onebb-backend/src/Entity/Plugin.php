<?php

namespace App\Entity;

use App\Repository\PluginRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PluginRepository::class)
 * @ApiResource(
 *      collectionOperations={
            "get"={
                "security"="is_granted('ROLE_PLUGIN_GET')"
                }, 
        },
 *      itemOperations={
 *           "get"={
                "security"="is_granted('ROLE_PLUGIN_GET')"
                },
 *           "put"={
                "denormalization_context"={"groups": {"plugin:put"}},
                "security"="is_granted('ROLE_PLUGIN_EDIT')"},
 *           }
 *  )
 */
class Plugin
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
    private $name;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $version;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"plugin:put"}) 
     */
    private $install;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"plugin:put"}) 
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $snippet;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $events = [];

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

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getInstall(): ?bool
    {
        return $this->install;
    }

    public function setInstall(?bool $install): self
    {
        $this->install = $install;

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

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function setMeta(?string $meta): self
    {
        $this->meta = $meta;

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

    public function getSnippet(): ?string
    {
        return $this->snippet;
    }

    public function setSnippet(?string $snippet): self
    {
        $this->snippet = $snippet;

        return $this;
    }

    public function getEvents(): ?array
    {
        return $this->events;
    }

    public function setEvents(?array $events): self
    {
        $this->events = $events;

        return $this;
    }
}
