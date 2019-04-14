<?php


namespace App\ApplicationService;


use App\Domain\CheckPointRepositoryInterface;
use App\Entity\CheckPoint;

class CheckPointApplicationService
{
    private $checkPointRepository;
    public function __construct(CheckPointRepositoryInterface
                                $checkPointRepository)
    {
        $this->checkPointRepository = $checkPointRepository;
    }
    public function addCheckPoint($checkPoint){
        $this->checkPointRepository->insertCheckPoint($checkPoint);
    }

    public function changeCheckPointDone(CheckPoint $checkPoint)
    {
        $this->checkPointRepository->changeCheckPointDone($checkPoint);
    }


}