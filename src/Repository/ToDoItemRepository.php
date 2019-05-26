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

    public function findToDosByProject(?string $term,$project)
    {
        $qb=$this->createQueryBuilder('t');

        if ($term) {
            $qb
                ->leftJoin('t.tags','tags')
                ->addSelect('tags')
                ->andWhere('t.name LIKE :term OR tags.name LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }
        return $qb
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


    public function findToDoItemsByProject($q,string $slug): array
    {
        $toDoItems=$this->findToDosByProject($q,$slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints(),$toDoItem->getHeading());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }


    public function findTodayToDos(?string $term,$workspace)
    {
        $datetime = new \DateTime();
        $date= $datetime->format('Y-m-d');

        $qb=$this->createQueryBuilder('t');

        if ($term) {
            $qb
                ->leftJoin('t.tags','tags')
                ->addSelect('tags')
                ->andWhere('t.name LIKE :term OR tags.name LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb
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

    public function findUpcomingToDos(?string $term,$workspace)
    {
        $datetime = new \DateTime();
        $date= $datetime->format('Y-m-d');

        $qb=$this->createQueryBuilder('t');

        if ($term) {
            $qb
                ->leftJoin('t.tags','tags')
                ->addSelect('tags')
                ->andWhere('t.name LIKE :term OR tags.name LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb
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

    public function findCompletedToDos(?string $term,$workspace)
    {
        $qb=$this->createQueryBuilder('t');

        if ($term) {
            $qb
                ->leftJoin('t.tags','tags')
                ->addSelect('tags')
                ->andWhere('t.name LIKE :term OR tags.name LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb
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

    public function findAnytimeToDos(?string $term,$workspace)
    {

        $qb=$this->createQueryBuilder('t');

        if ($term) {
            $qb
                ->leftJoin('t.tags','tags')
                ->addSelect('tags')
                ->andWhere('t.name LIKE :term OR tags.name LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb
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
    public function findSomedayToDos(?string $term,$workspace)
    {
        $qb=$this->createQueryBuilder('t');

        if ($term) {
            $qb
                ->leftJoin('t.tags','tags')
                ->addSelect('tags')
                ->andWhere('t.name LIKE :term OR tags.name LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb
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
                $toDoItem->getCheckPoints(),$toDoItem->getHeading());
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

    public function findTodayToDoItems($q,string $slug): array
    {
        $toDoItems=$this->findTodayToDos($q,$slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints(),$toDoItem->getHeading());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }
    public function findUpcomingToDoItems($q,string $slug): array
    {
        $toDoItems=$this->findUpcomingToDos($q,$slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints(),$toDoItem->getHeading());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }
    public function findCompletedToDoItems($q,string $slug): array
    {
        $toDoItems=$this->findCompletedToDos($q,$slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints(),$toDoItem->getHeading());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }
    public function findAnytimeToDoItems($q,string $slug): array
    {
        $toDoItems=$this->findAnytimeToDos($q,$slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints(),$toDoItem->getHeading());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }
    public function findSomedayToDoItems($q,string $slug): array
    {
        $toDoItems=$this->findSomedayToDos($q,$slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints(),$toDoItem->getHeading());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;
    }

    public function insertToDo(ToDoItem $toDoItem): void
    {
        $this->em->persist($toDoItem);
        $this->em->flush();
    }

    public function findToDoBySlug(string $slug): ToDoItem
    {

        return $this->findOneBy(array('slug'=>$slug));
    }

    public function findAllToDos($workspace){
        $datetime = new \DateTime();
        $date= $datetime->format('Y-m-d');

        $qb=$this->createQueryBuilder('t');

        return $qb
            ->leftJoin('t.project', 'project')
            ->leftJoin('project.workspace','workspace')
            ->andWhere('workspace.slug = :workspaceSlug')
            ->setParameter('workspaceSlug', $workspace)
            ->andWhere('t.calendarDate>:date_now OR t.calendarDate<=:date_now')
            //->andWhere('t.calendarDate<=:date_now')
            ->setParameter('date_now', $date)
            ->orderBy('t.calendarDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllToDoItems(string $slug): array
    {
        $toDoItems=$this->findAllToDos($slug);
        $toDoItemDtos=array();
        foreach ($toDoItems as $toDoItem){
            $toDoDto=new ToDoItemDTO($toDoItem->getName(),$toDoItem->getCalendarDate(),
                $toDoItem->getTags(),$toDoItem->getProject(), $toDoItem->getDone(),
                $toDoItem->getSlug(), $toDoItem->getWish(),$toDoItem->getDescription(), $toDoItem->getDeadline(),
                $toDoItem->getCheckPoints(),$toDoItem->getHeading());
            array_push($toDoItemDtos,$toDoDto);

        }

        return $toDoItemDtos;

    }
}
