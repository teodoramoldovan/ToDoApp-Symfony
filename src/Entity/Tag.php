<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ToDoItem", mappedBy="tags")
     */
    private $toDoItems;

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
            $toDoItem->addTag($this);
        }

        return $this;
    }

    public function removeToDoItem(ToDoItem $toDoItem): self
    {
        if ($this->toDoItems->contains($toDoItem)) {
            $this->toDoItems->removeElement($toDoItem);
            $toDoItem->removeTag($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getName();
    }
}
