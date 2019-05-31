<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\MaxDepth;

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
    private $toDos;

    public function __construct()
    {
        $this->toDos = new ArrayCollection();
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
    public function getToDos(): Collection
    {
        return $this->toDos;
    }

    public function addToDo(ToDoItem $toDoItem): self
    {
        if (!$this->toDos->contains($toDoItem)) {
            $this->toDos[] = $toDoItem;
            $toDoItem->addTag($this);
        }

        return $this;
    }

    public function removeToDo(ToDoItem $toDoItem): self
    {
        if ($this->toDos->contains($toDoItem)) {
            $this->toDos->removeElement($toDoItem);
            $toDoItem->removeTag($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getName();
    }
}
