<?php


namespace App\ApplicationService;


use App\Domain\ToDoItemRepositoryInterface;
use App\Entity\Project;
use App\Entity\ToDoItem;
use App\Entity\Workspace;

class ToDoItemApplicationService
{
    private $toDoItemRepository;
    public function __construct(ToDoItemRepositoryInterface
                                $toDoItemRepository)
    {
        $this->toDoItemRepository = $toDoItemRepository;
    }

    public function findToDosByProject($q,string $slug):array
    {
        return $this->toDoItemRepository
            ->findToDoItemsByProject($q,$slug);


    }
    public function changeToDoDone(ToDoItem $toDoItem):void
    {
        $this->toDoItemRepository->changeToDoDone($toDoItem);
    }
    public function findAllSearch($q, string $slug){
        return $this->toDoItemRepository
            ->findAllSearch($q,$slug);
    }
    public function delete($toDoItem){
        $this->toDoItemRepository->deleteToDo($toDoItem);
    }
    public function editToDoItem($toDoItem){
        $this->toDoItemRepository->updateToDo($toDoItem);
    }
    public function addToDoItem($toDoItem){
        $this->toDoItemRepository->insertToDo($toDoItem);
    }
    public function findTodayToDos($q,string $slug){
        return $this->toDoItemRepository
            ->findTodayToDoItems($q,$slug);
    }
    public function findUpcomingToDos($q,string $slug){
        return $this->toDoItemRepository
            ->findUpcomingToDoItems($q,$slug);
    }
    public function findCompletedToDos($q,string $slug){
        return $this->toDoItemRepository
            ->findCompletedToDoItems($q,$slug);
    }
    public function findAnytimeToDos($q,string $slug){
        return $this->toDoItemRepository
            ->findAnytimeToDoItems($q,$slug);
    }
    public function findSomedayToDos($q,string $slug){
        return $this->toDoItemRepository
            ->findSomedayToDoItems($q,$slug);
    }
    public function findToDoBySlug(string $slug){
        return $this->toDoItemRepository
            ->findToDoBySlug($slug);
    }
    public function extractTags(string $title):array
    {
        return explode("#",$title);
    }


}