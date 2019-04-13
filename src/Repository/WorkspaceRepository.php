<?php

namespace App\Repository;


use App\Domain\Model\WorkspaceDTO;
use App\Domain\WorkspaceRepositoryInterface;
use App\Entity\Workspace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Workspace|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workspace|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workspace[]    findAll()
 * @method Workspace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkspaceRepository extends ServiceEntityRepository implements WorkspaceRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Workspace::class);
    }
    public function findInboxByUser(int $userId):?Workspace
    {
        $inbox="Inbox";

        return $this->createQueryBuilder('w')


            ->andWhere('w.name LIKE :inbox')
            ->setParameter('inbox','%'.$inbox.'%')
            ->leftJoin('w.user', 'user')
            ->andWhere('user.id = :userId')
            ->setParameter('userId', $userId)

            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function findWorkspace(int $userId): WorkspaceDTO
    {
        $workspace=$this->findInboxByUser($userId);
        $workspaceDto=new WorkspaceDTO();
        $workspaceDto->name=$workspace->getName();
        $workspaceDto->projects=$workspace->getProjects();
        $workspaceDto->slug=$workspace->getSlug();
        return $workspaceDto;
    }

    public function findWorkspacesByUser($userId)
    {
        return $this->createQueryBuilder('w')
            ->leftJoin('w.user', 'user')
            ->andWhere('user.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
            ;
    }

}
