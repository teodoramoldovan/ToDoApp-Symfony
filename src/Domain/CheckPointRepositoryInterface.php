<?php


namespace App\Domain;


use App\Entity\CheckPoint;

interface CheckPointRepositoryInterface
{
    public function insertCheckPoint(CheckPoint $checkPoint):void;
    public function changeCheckPointDone(CheckPoint $checkPoint):void;

}