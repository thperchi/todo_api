<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TodoListRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TodoListRepository::class)
 * @ApiResource(
 *  denormalizationContext={"groups"={"list:write"}},
 *  itemOperations={
 *      "get",
 *      "delete"={"security"="object.getUser() == user"},
 *      "put"={"security"="object.getUser() == user"},
 *      "patch"={"security"="object.getUser() == user"}
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
 */
class TodoList implements UserOwnedInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list:write", "user:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"list:write", "user:read"})
     * @var \DateTime
     */
    private $due_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="lists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="list", orphanRemoval=true, cascade={"persist"})
     * @Groups({"list:write"})
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(?\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setList($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getList() === $this) {
                $task->setList(null);
            }
        }

        return $this;
    }
}
