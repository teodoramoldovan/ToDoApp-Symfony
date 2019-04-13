<?php


namespace App\Domain;


use App\Entity\User;
use App\Entity\Workspace;

interface UserRepositoryInterface
{
    public function insertUser(User $user, Workspace $workspace):void ;
}