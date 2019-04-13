<?php


namespace App\Domain;

use App\Entity\ToDoItem;

interface ToDoItemRepositoryInterface
{
    public function findToDoItemsByProject(string $slug):array ;
    public function findAllSearch($q,string $slug):array;
    public function changeToDoDone(ToDoItem $toDoItem):void;
    public function deleteToDo(ToDoItem $toDoItem):void;
    public function updateToDo(ToDoItem $toDoItem):void;
    public function findTodayToDoItems(string $slug):array;
    public function findUpcomingToDoItems(string $slug):array;
    public function findCompletedToDoItems(string $slug):array;
    public function findAnytimeToDoItems(string $slug):array;
    public function findSomedayToDoItems(string $slug):array;

}