<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ApiResource(
 *  itemOperations={
 *      "get",
 *      "delete"={"security"="object.getList().getUser() == user"},
 *      "put"={"security"="object.getList().getUser() == user"},
 *      "patch"={"security"="object.getList().getUser() == user"},
 *      "validateTask"={
 *          "method"="get",
 *          "path"="task/{id}/validate",
 *          "controller"=ValidateTaskController::class,
 *      }
 *  }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"is_done"})
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_done = false;

    /**
     * @ORM\ManyToOne(targetEntity=TodoList::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $list;

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

    public function getIsDone(): ?bool
    {
        return $this->is_done;
    }

    public function setIsDone(bool $is_done): self
    {
        $this->is_done = $is_done;

        return $this;
    }

    public function getList(): ?TodoList
    {
        return $this->list;
    }

    public function setList(?TodoList $list): self
    {
        $this->list = $list;

        return $this;
    }
}
