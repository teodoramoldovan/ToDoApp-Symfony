<?php


namespace App\Domain;


use App\Domain\Model\ProjectDTO;
use App\Entity\Project;

interface ProjectRepositoryInterface
{
    public function findProjectBySlug(string $slug):ProjectDTO ;
    public function findProjects(string $slug):array ;
    public function findProjectById(int $id):ProjectDTO;
    public function insertProject(Project $project):void;

}