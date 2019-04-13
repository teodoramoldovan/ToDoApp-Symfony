<?php


namespace App\ApplicationService;


use App\Domain\Model\ProjectDTO;
use App\Domain\ProjectRepositoryInterface;

class ProjectApplicationService
{
    private $projectRepository;
    public function __construct(ProjectRepositoryInterface
                                $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function findProjectBySlug(string $slug):ProjectDTO
    {
        return $this->projectRepository
            ->findProjectBySlug($slug);

    }
    public function findProjectsByWorkspace(string $slug):array
    {
        return $this->projectRepository->findProjects($slug);


    }

}