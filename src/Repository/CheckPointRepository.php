<?php

namespace App\Repository;

use App\Entity\CheckPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CheckPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckPoint[]    findAll()
 * @method CheckPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckPointRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CheckPoint::class);
    }


}
