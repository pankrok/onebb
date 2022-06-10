<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 * @ApiResource(
            
 *      collectionOperations={
            "get"={
                "normalization_context"={"groups": {"user"}},
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"
                }, 
            "post"={
                "security"="is_granted('ROLE_GROUPE_CREATE')",
             }
        },
 *      itemOperations={
 *           "get"={
                "normalization_context"={"groups": {"user"}},
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"
                },
 *           "put"={"security"="is_granted('ROLE_GROUPE_EDIT')"},
             "delete"={"security"="is_granted('ROLE_GROUPE_DELETE')"}
 *           }
 *  )
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user"}) 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"category", "board", "plot", "plot_subresource", "user"}) 
     */
    private $html_code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"board", "plot", "plot_subresource", "user"}) 
     */
    private $group_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"user"}) 
     */
    private $group_level;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="user_group")
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHtmlCode(): ?string
    {
        return $this->html_code;
    }

    public function setHtmlCode(string $html_code): self
    {
        $this->html_code = $html_code;

        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(string $group_name): self
    {
        $this->group_name = $group_name;

        return $this;
    }

    public function getGroupLevel(): ?int
    {
        return $this->group_level;
    }

    public function setGroupLevel(?int $group_level): self
    {
        $this->group_level = $group_level;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setUserGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUserGroup() === $this) {
                $user->setUserGroup(null);
            }
        }

        return $this;
    }

}
