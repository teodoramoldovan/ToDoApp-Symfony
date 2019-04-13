<?php


namespace App\Domain;


use App\Domain\Model\ProjectDTO;

interface ProjectRepositoryInterface
{
    public function findProjectBySlug(string $slug):ProjectDTO ;
    public function findProjects(string $slug):array ;

}