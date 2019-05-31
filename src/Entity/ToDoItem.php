<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ToDoItemRepository")
 */
class ToDoItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please add a name")
     */
    private $name;


    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $calendarDate;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="toDos")
     * @MaxDepth(1)
     */
    private $tags;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="toDoItems", cascade={"persist"})
     */
    private $project;

    /**
     * @ORM\Column(type="boolean")
     */
    private $done=false;

    /**
     * @ORM\Column(type="string", length=100,unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $wish;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deadline;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CheckPoint", mappedBy="toDoItem", orphanRemoval=true)

     */
    private $checkPoints;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $heading;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->checkPoints = new ArrayCollection();
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

    public function getCalendarDate(): ?\DateTimeInterface
    {
        return $this->calendarDate;
    }

    public function setCalendarDate(?\DateTimeInterface $calendarDate): self
    {
        $this->calendarDate = $calendarDate;

        return $this;
    }


    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

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

    public function changeDone(): self
    {
        if($this->done==true){
            $this->done=false;
        }else $this->done=true;

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

    public function getWish(): ?bool
    {
        return $this->wish;
    }

    public function setWish(?bool $wish): self
    {
        $this->wish = $wish;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * @return Collection|CheckPoint[]
     */
    public function getCheckPoints(): Collection
    {
        return $this->checkPoints;
    }

    public function addCheckPoint(CheckPoint $checkPoint): self
    {
        if (!$this->checkPoints->contains($checkPoint)) {
            $this->checkPoints[] = $checkPoint;
            $checkPoint->setToDoItem($this);
        }

        return $this;
    }

    public function removeCheckPoint(CheckPoint $checkPoint): self
    {
        if ($this->checkPoints->contains($checkPoint)) {
            $this->checkPoints->removeElement($checkPoint);
            // set the owning side to null (unless already changed)
            if ($checkPoint->getToDoItem() === $this) {
                $checkPoint->setToDoItem(null);
            }
        }

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function setHeading(?string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

}
