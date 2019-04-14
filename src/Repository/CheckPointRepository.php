<?php

namespace App\Repository;

use App\Domain\CheckPointRepositoryInterface;
use App\Entity\CheckPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CheckPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckPoint[]    findAll()
 * @method CheckPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckPointRepository extends ServiceEntityRepository implements CheckPointRepositoryInterface
{
    private $em;
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, CheckPoint::class);
        $this->em=$em;
    }


    public function insertCheckPoint(CheckPoint $checkPoint): void
    {
        $this->em->persist($checkPoint);
        $this->em->flush();
    }

    public function changeCheckPointDone(CheckPoint $checkPoint): void
    {
        $checkPoint->changeDone();

        $this->em->flush();
    }
}
