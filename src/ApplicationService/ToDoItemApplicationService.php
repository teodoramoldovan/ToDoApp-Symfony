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

    public function findToDosByProject(string $slug):array
    {
        return $this->toDoItemRepository
            ->findToDoItemsByProject($slug);


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
    public function findTodayToDos(string $slug){
        return $this->toDoItemRepository
            ->findTodayToDoItems($slug);
    }
    public function findUpcomingToDos(string $slug){
        return $this->toDoItemRepository
            ->findUpcomingToDoItems($slug);
    }
    public function findCompletedToDos(string $slug){
        return $this->toDoItemRepository
            ->findCompletedToDoItems($slug);
    }
    public function findAnytimeToDos(string $slug){
        return $this->toDoItemRepository
            ->findAnytimeToDoItems($slug);
    }
    public function findSomedayToDos(string $slug){
        return $this->toDoItemRepository
            ->findSomedayToDoItems($slug);
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