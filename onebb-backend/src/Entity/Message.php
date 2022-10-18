<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 * @ApiFilter(DateFilter::class, properties={"created_at"})
 * @ApiFilter(SearchFilter::class, properties={"om"}) 
 * @ApiResource( 
    order={"created_at": "DESC"},
    security="is_granted('ROLE_USER')",
    collectionOperations={
            "post"={
                "denormalization_context"={"groups": {"post"}}
            },
            "get"={
                "normalization_context"={"groups": {"user"}}
            }
        },
    itemOperations={"get"}
   )
*/
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     */
    private $id;

    

    /**
     * @ORM\Column(type="text")
     * @Groups({"user", "post"})
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=OneMessenger::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user", "post"})
     */
    private $om;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @Groups({"user"})
     */
    private $sender;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOm(): ?OneMessenger
    {
        return $this->om;
    }

    public function setOm(?OneMessenger $om): self
    {
        $this->om = $om;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }
}
