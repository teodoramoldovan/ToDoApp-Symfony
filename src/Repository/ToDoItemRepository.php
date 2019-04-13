<?php

namespace App\Repository;

use App\Domain\Model\ToDoItemDTO;
use App\Domain\ToDoItemRepositoryInterface;
use App\Entity\ToDoItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ToDoItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDoItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDoItem[]    findAll()
 * @method ToDoItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoItemRepository extends ServiceEntityRepository implements ToDoItemRepositoryInterface
{
    private $em;
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, ToDoItem::class);
        $this->em=$em;
    }

    public function findToDosByProject($project)
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.project', 'project')
            ->andWhere('project.slug = :projectSlug')
            ->setParameter('projectSlug', $project)
            ->getQuery()
            ->getResult()
            ;
    }

    public function changeToDoDone(ToDoItem $toDoItem): void
    {
        $toDoItem->changeDone();

        $this->em->flush();
    }


    public function findToDoItemsByProject(string $slug): array
    {
        $toDoItems=$this->findToDosByProject($slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }


    public function findTodayToDos($workspace)
    {
        $datetime = new \DateTime();
        $date= $datetime->format('Y-m-d');

        return $this->createQueryBuilder('t')
            ->leftJoin('t.project', 'project')
            ->leftJoin('project.workspace','workspace')
            ->andWhere('workspace.slug = :workspaceSlug')
            ->setParameter('workspaceSlug', $workspace)
            ->andWhere('t.calendarDate=:date_now')
            ->setParameter('date_now', $date)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findUpcomingToDos($workspace)
    {
        $datetime = new \DateTime();
        $date= $datetime->format('Y-m-d');

        return $this->createQueryBuilder('t')
            ->leftJoin('t.project', 'project')
            ->leftJoin('project.workspace','workspace')
            ->andWhere('workspace.slug = :workspaceSlug')
            ->setParameter('workspaceSlug', $workspace)
            ->andWhere('t.calendarDate>:date_now')
            ->setParameter('date_now', $date)
            ->orderBy('t.calendarDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findCompletedToDos($workspace)
    {

        return $this->createQueryBuilder('t')
            ->leftJoin('t.project', 'project')
            ->leftJoin('project.workspace','workspace')
            ->andWhere('workspace.slug = :workspaceSlug')
            ->setParameter('workspaceSlug', $workspace)
            ->andWhere('t.done=:done')
            ->setParameter('done', true)
            ->orderBy('t.calendarDate', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAnytimeToDos($workspace)
    {

        return $this->createQueryBuilder('t')
            ->leftJoin('t.project', 'project')
            ->leftJoin('project.workspace','workspace')
            ->andWhere('workspace.slug = :workspaceSlug')
            ->setParameter('workspaceSlug', $workspace)
            ->andWhere('t.calendarDate is null')
            ->andWhere('t.wish is null')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findSomedayToDos($workspace)
    {

        return $this->createQueryBuilder('t')
            ->leftJoin('t.project', 'project')
            ->leftJoin('project.workspace','workspace')
            ->andWhere('workspace.slug = :workspaceSlug')
            ->setParameter('workspaceSlug', $workspace)
            ->andWhere('t.calendarDate is null')
            ->andWhere('t.wish=:exists')
            ->setParameter('exists',true)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string|null $term
     * @return ToDoItem[]
     */
    public function findAllWithSearch(?string $term,$workspace)
    {
        $qb = $this->createQueryBuilder('t');
        if ($term) {
            $qb
                ->leftJoin('t.tags','tags')
                ->addSelect('tags')
                ->andWhere('t.name LIKE :term OR tags.name LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }
        return $qb->leftJoin('t.project', 'project')
            ->leftJoin('project.workspace','workspace')
            ->andWhere('workspace.slug = :workspaceSlug')
            ->setParameter('workspaceSlug', $workspace)
            ->orderBy('t.project', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    public function findAllSearch($q, string $slug): array
    {
        $toDoItems=$this->findAllWithSearch($q,$slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }

    public function deleteToDo(ToDoItem $toDoItem): void
    {
        $this->em->remove($toDoItem);
        $this->em->flush();
    }

    public function updateToDo(ToDoItem $toDoItem): void
    {
        $this->em->persist($toDoItem);
        $this->em->flush();
    }

    public function findTodayToDoItems(string $slug): array
    {
        $toDoItems=$this->findTodayToDos($slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }
    public function findUpcomingToDoItems(string $slug): array
    {
        $toDoItems=$this->findUpcomingToDos($slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }
    public function findCompletedToDoItems(string $slug): array
    {
        $toDoItems=$this->findCompletedToDos($slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }
    public function findAnytimeToDoItems(string $slug): array
    {
        $toDoItems=$this->findAnytimeToDos($slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }
    public function findSomedayToDoItems(string $slug): array
    {
        $toDoItems=$this->findSomedayToDos($slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }

}
