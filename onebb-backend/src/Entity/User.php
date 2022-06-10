<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Api\SomeRandomController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 * @ApiFilter(PropertyFilter::class)
 * @ApiFilter(OrderFilter::class, properties={"id", "username", "email"})
 * @ApiResource(
 *      attributes={"security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"},
        collectionOperations={
            "post"={
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')", 
                "normalization_context"={"groups": {"board"}}
            },
            "get"={
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')", 
                "normalization_context"={"groups": {"board"}}
            }
        },
 *     itemOperations={
 *         "put"={"security_post_denormalize"="is_granted('ROLE_ADMIN') or object.user == user"},
           "put_img"={
                "method"="PUT",
                "groups": {"avatar"},
                "path"="/users/{id}/img",
                "controller"="App\Controller\UserAvatarController",
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY') or object.user == user"
            },
            "put_admin"={
               "security"="is_granted('ROLE_USER_EDIT')",
               "path"="/users/admin/{id}",
               "method"="PUT",
           },
           "delete"={"security"="is_granted('ROLE_MODERATOR')"}, 
           "get"={
                "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
                "normalization_context"={"groups": {"user"}}
           },
           "get_admin"={
               "security"="is_granted('ROLE_USER_EDIT')",
               "path"="/users/admin/{id}",
               "method"="GET",
               "normalization_context"={"groups": {"user", "user_admin", "user_admin_acp"}}
           }
 *     }
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"board", "plot", "plot_subresource", "user"}) 
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     * @Groups({"category", "plot", "plot_subresource", "board", "user"}) 
     * @Assert\Length(
     *      min = 3,
     *      max = 32,
     *      minMessage = "Your login must be at least {{ limit }} characters long",
     *      maxMessage = "Your login cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user_admin"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 8,
     *      max = 64,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     * @Groups({"user"})
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"category", "board", "plot", "plot_subresource", "user"}) 
     */
    private $banned = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user_admin"})    
     */
    private $verified = false;

    /**
     * @ORM\Column(type="boolean")   
     * @Groups({"user_admin_acp"})       
     */
    private $acp_enable = false;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="user")
     * @Groups({"category", "board", "plot", "plot_subresource", "user"})
     */
    private $user_group;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"category", "board", "plot", "plot_subresource", "user", "avatar"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"category", "board", "plot", "plot_subresource", "user"})
     */
    private $slug;
    
    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user", orphanRemoval=false)
     * @ApiSubresource({"user", "board"})
     */
    private $posts;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"board", "plot", "plot_subresource", "user"})
     */
    private $posts_no;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"board", "plot", "plot_subresource", "user"})
     */
    private $plots_no;

    /**
     * @ORM\OneToOne(targetEntity=UserValidation::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $userValidation;
    
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }
    
    public function __toString(): string
    {
        return $this->username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }
    
    public function getAcpEnabled(): ?bool
    {
        return $this->acp_enable;
    }

    public function setAcpEnabled(bool $acp_enable): self
    {
        $this->acp_enable = $acp_enable;

        return $this;
    }

    public function getUserGroup(): ?Group
    {
        return $this->user_group;
    }

    public function setUserGroup(?Group $user_group): self
    {
        $this->user_group = $user_group;

        return $this;
    }

    public function getAvatar(): ?string
    {        
        return $this->avatar ?  'upload/img/' . $this->avatar : '/img/avatar.png' ;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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
    
    public function computeSlug(SluggerInterface $slugger): self
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = (string) $slugger->slug((string) $this)->lower();
        }
        
        return $this;
    }

    public function getPostsNo(): ?int
    {
        return $this->posts_no;
    }

    public function setPostsNo(int $posts_no): self
    {
        $this->posts_no = $posts_no;

        return $this;
    }
    
    public function incrementPostsNo(): self
    {
        if (!is_int($this->posts_no)) {
            $this->posts_no = 0;
        }
        
        $this->posts_no++;

        return $this;
    }

    public function getPlotsNo(): ?int
    {
        return $this->plots_no;
    }

    public function setPlotsNo(int $plots_no): self
    {
        $this->plots_no = $plots_no;

        return $this;
    }
    
    public function incrementPlotsNo(): self
    {
        if (!is_int($this->plots_no)) {
            $this->plots_no = 0;
        }
        
        $this->plots_no++;

        return $this;
    }
    
    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setPlot($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getPlot() === $this) {
                $post->setPlot(null);
            }
        }

        return $this;
    }

    public function getUserValidation(): ?UserValidation
    {
        return $this->userValidation;
    }

    public function setUserValidation(UserValidation $userValidation): self
    {
        // set the owning side of the relation if necessary
        if ($userValidation->getUser() !== $this) {
            $userValidation->setUser($this);
        }

        $this->userValidation = $userValidation;

        return $this;
    }
}
