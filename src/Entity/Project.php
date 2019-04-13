<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Add a name")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100,unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ToDoItem", mappedBy="project", orphanRemoval=true)
     */
    private $toDoItems;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workspace", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workspace;

    public function __construct()
    {
        $this->toDoItems = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection|ToDoItem[]
     */
    public function getToDoItems(): Collection
    {
        return $this->toDoItems;
    }

    public function addToDoItem(ToDoItem $toDoItem): self
    {
        if (!$this->toDoItems->contains($toDoItem)) {
            $this->toDoItems[] = $toDoItem;
            $toDoItem->setProject($this);
        }

        return $this;
    }

    public function removeToDoItem(ToDoItem $toDoItem): self
    {
        if ($this->toDoItems->contains($toDoItem)) {
            $this->toDoItems->removeElement($toDoItem);
            // set the owning side to null (unless already changed)
            if ($toDoItem->getProject() === $this) {
                $toDoItem->setProject(null);
            }
        }

        return $this;
    }

    public function getWorkspace(): ?Workspace
    {
        return $this->workspace;
    }

    public function setWorkspace(?Workspace $workspace): self
    {
        $this->workspace = $workspace;

        return $this;
    }
}
