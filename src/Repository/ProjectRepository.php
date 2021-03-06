<?php

namespace App\Repository;

use App\Domain\Model\ProjectDTO;
use App\Domain\ProjectRepositoryInterface;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository implements ProjectRepositoryInterface
{
    private $em;
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Project::class);
        $this->em=$em;
    }

    public function findProjectsByWorkspace($workspace)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.workspace', 'workspace')
            ->andWhere('workspace.slug = :workspaceSlug')
            ->setParameter('workspaceSlug', $workspace)
            ->getQuery()
            ->getResult()
            ;
    }


    public function findProjectBySlug(string $slug): ProjectDTO
    {
        $project=$this->findOneBy(array('slug' => $slug));
        return new ProjectDTO($project->getName(),$project->getDescription(),
            $project->getSlug(),$project->getToDoItems(), $project->getWorkspace());
    }

    public function findProjects(string $slug): array
    {
        $projects=$this->findProjectsByWorkspace($slug);
        $projectDtos=array();
        foreach ($projects as $project){
            $projectDto=new ProjectDTO($project->getName(),$project->getDescription(),
                $project->getSlug(),$project->getToDoItems(), $project->getWorkspace());
            array_push($projectDtos,$projectDto);

        }

        return $projectDtos;
    }

    public function findProjectById(int $id): ProjectDTO
    {
        $project=$this->findOneBy(array('id' => $id));
        return new ProjectDTO($project->getName(),$project->getDescription(),
            $project->getSlug(),$project->getToDoItems(), $project->getWorkspace());
    }

    public function queryProjectsByWorkspace($workspace)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.workspace', 'workspace')
            ->andWhere('workspace.slug = :workspaceSlug')
            ->setParameter('workspaceSlug', $workspace)
            ;
    }

    public function insertProject(Project $project): void
    {
        $this->em->persist($project);
        $this->em->flush();
    }
}
