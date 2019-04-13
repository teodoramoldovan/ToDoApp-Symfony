<?php


namespace App\ApplicationService;


use App\Domain\Model\WorkspaceDTO;
use App\Domain\WorkspaceRepositoryInterface;

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

}