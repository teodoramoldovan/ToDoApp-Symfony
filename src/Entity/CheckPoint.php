<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CheckPointRepository")
 */
class CheckPoint
{
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
     * @ORM\Column(type="boolean")
     */
    private $done=false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ToDoItem", inversedBy="checkPoints",cascade={"persist"})
     */
    private $toDoItem;

    /**
     * @ORM\Column(type="string", length=100,unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

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

    public function getDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): self
    {
        $this->done = $done;

        return $this;
    }

    public function getToDoItem(): ?ToDoItem
    {
        return $this->toDoItem;
    }

    public function setToDoItem(?ToDoItem $toDoItem): self
    {
        $this->toDoItem = $toDoItem;

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
    public function changeDone(): self
    {
        if($this->done==true){
            $this->done=false;
        }else $this->done=true;

        return $this;
    }
}
