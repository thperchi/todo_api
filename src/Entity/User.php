<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"={"user:read"}},
 *  denormalizationContext={"groups"={"user:write"}},
 *  itemOperations={
 *      "get",
 *      "userLists"={
 *          "method"="get",
 *          "path"="users/{id}/lists",
 *          "controller"=UserListsController::class,
 *      }
 *  },
 *  collectionOperations={
 *      "get",
 *      "post"
 *  }
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:write", "user:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:write", "user:read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:write"})
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=TodoList::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"user:read"})
     */
    private $lists;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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
        $this->plainPassword = null;
    }

    /**
     * @return Collection<int, TodoList>
     */
    public function getLists(): Collection
    {
        return $this->lists;
    }

    public function addList(TodoList $todoList): self
    {
        if (!$this->lists->contains($todoList)) {
            $this->lists[] = $todoList;
            $todoList->setUser($this);
        }

        return $this;
    }

    public function removeList(TodoList $todoList): self
    {
        if ($this->lists->removeElement($todoList)) {
            // set the owning side to null (unless already changed)
            if ($todoList->getUser() === $this) {
                $todoList->setUser(null);
            }
        }

        return $this;
    }
    
    public static function createFromPayload($username, array $payload)
    {
        $user = New User();
        $user->setEmail($username);
        return $user;
    }
}
