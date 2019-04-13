<?php


namespace App\Domain\Model;


use Symfony\Component\Validator\Constraints as Assert;

class WorkspaceDTO
{
    /**
     * @Assert\NotNull()
     */
    public $name;
    public $projects;
    /**
     * @Assert\NotNull()
     */
    public $slug;


}