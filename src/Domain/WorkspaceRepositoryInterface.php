<?php


namespace App\Domain;


use App\Domain\Model\WorkspaceDTO;
use App\Entity\Workspace;

interface WorkspaceRepositoryInterface
{
    public function findWorkspace(int $userId):WorkspaceDTO;
    public function findSimpleWorkspace(int $userId):Workspace;
    public function insertWorkspace(Workspace $workspace):void;

    public function findCustomWorkspaces(?int $userId):array;


}