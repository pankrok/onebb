<?php

namespace App\Entity;

use App\Repository\OneMessengerRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ORM\Entity(repositoryClass=OneMessengerRepository::class)
 * @ApiFilter(DateFilter::class, properties={"updated_at"})
* @ApiResource( 
    order={"updated_at": "DESC"},
    security="is_granted('ROLE_USER')",
    collectionOperations={
            "post"={
                "normalization_context"={"groups": {"msg"}}
            },
            "get"={
                "normalization_context"={"groups": {"board"}}
            }
        },
    itemOperations={"get"}
   )
*/

class OneMessenger
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"board", "msg"})
     */
    private $id;


    /**
     * @ORM\Column(type="datetime")
     * @Groups({"board"})
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"board", "msg"})
     * @ApiFilter(SearchFilter::class, properties={"users.id"}, strategy="iexact") 
     */
    private $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId($id): self
    {
        if ($this->id === null) {
            $this->id = $id;
        }
        
        return $this;
    }


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }


}
