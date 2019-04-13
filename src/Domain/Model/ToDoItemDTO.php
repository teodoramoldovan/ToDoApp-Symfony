<?php


namespace App\Domain\Model;


use Symfony\Component\Validator\Constraints as Assert;

class ToDoItemDTO
{

    /**
     * @Assert\NotNull()
     */
    public $name;
    public $calendarDate;
    public $tags;
    /**
     * @Assert\NotNull()
     */
    public $project;
    public $done;
    /**
     * @Assert\NotNull()
     */
    public $slug;
    public $wish;
    public $description;
    public $deadline;
    public $checkPoints;

    /**
     * ToDoItemDTO constructor.
     * @param $name
     * @param $calendarDate
     * @param $tags
     * @param $project
     * @param $done
     * @param $slug
     * @param $wish
     * @param $description
     * @param $deadline
     * @param $checkPoints
     */
    public function __construct($name, $calendarDate, $tags, $project, $done, $slug, $wish, $description, $deadline, $checkPoints)
    {
        $this->name = $name;
        $this->calendarDate = $calendarDate;
        $this->tags = $tags;
        $this->project = $project;
        $this->done = $done;
        $this->slug = $slug;
        $this->wish = $wish;
        $this->description = $description;
        $this->deadline = $deadline;
        $this->checkPoints = $checkPoints;
    }

    public function changeDone(): self
    {
        if($this->done==true){
            $this->done=false;
        }else $this->done=true;

        return $this;
    }


}