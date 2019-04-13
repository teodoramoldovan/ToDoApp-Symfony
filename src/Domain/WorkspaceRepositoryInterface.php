<?php


namespace App\Domain;


use App\Domain\Model\WorkspaceDTO;

interface WorkspaceRepositoryInterface
{
    public function findWorkspace(int $userId):WorkspaceDTO;


}