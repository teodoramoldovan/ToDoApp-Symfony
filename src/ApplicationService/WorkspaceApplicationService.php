<?php


namespace App\ApplicationService;


use App\Domain\Model\WorkspaceDTO;
use App\Domain\WorkspaceRepositoryInterface;
use App\Entity\Workspace;

class WorkspaceApplicationService
{
    private $workspaceRepository;
    public function __construct(WorkspaceRepositoryInterface
                                $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    public function findWorkspace(int $userId):WorkspaceDTO
    {
        $workspace=$this->workspaceRepository
            ->findWorkspace($userId);
        return $workspace;

    }
    public function findSimpleWorkspace(int $userId):Workspace{
        return $this->workspaceRepository
            ->findSimpleWorkspace($userId);
    }
    public function addWorkspace(Workspace $workspace):void
    {
        $this->workspaceRepository->insertWorkspace($workspace);
    }

    public function findCustomWorkspaces(?int $userId)
    {
        return $this->workspaceRepository->findCustomWorkspaces($userId);
    }
}