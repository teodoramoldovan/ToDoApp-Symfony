<?php


namespace App\Domain\Model;


class ProjectDTO
{

    public $name;
    public $description;
    public $slug;
    public $toDoItems;
    public $workspace;

    /**
     * ProjectDTO constructor.
     * @param $name
     * @param $description
     * @param $slug
     * @param $toDoItems
     * @param $workspace
     */
    public function __construct($name, $description, $slug, $toDoItems, $workspace)
    {
        $this->name = $name;
        $this->description = $description;
        $this->slug = $slug;
        $this->toDoItems = $toDoItems;
        $this->workspace = $workspace;
    }


}