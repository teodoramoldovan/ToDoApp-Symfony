<?php


namespace App\Form\Model;


use Symfony\Component\Validator\Constraints as Assert;

class ToDoItemFormModel
{
    /**
     * @Assert\NotBlank(message="Please enter something")
     */
    public $unprocessedToDO;
    /**
     * @Assert\NotBlank(message="Please enter something")
     */
    public $project;

}