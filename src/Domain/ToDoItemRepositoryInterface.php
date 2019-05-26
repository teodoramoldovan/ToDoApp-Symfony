<?php


namespace App\Domain;

use App\Entity\ToDoItem;

interface ToDoItemRepositoryInterface
{
    public function findToDoItemsByProject($q,string $slug):array ;
    public function findAllSearch($q,string $slug):array;
    public function changeToDoDone(ToDoItem $toDoItem):void;
    public function deleteToDo(ToDoItem $toDoItem):void;
    public function updateToDo(ToDoItem $toDoItem):void;
    public function insertToDo(ToDoItem $toDoItem):void;
    public function findTodayToDoItems($q,string $slug):array;
    public function findUpcomingToDoItems($q,string $slug):array;
    public function findCompletedToDoItems($q,string $slug):array;
    public function findAnytimeToDoItems($q,string $slug):array;
    public function findSomedayToDoItems($q,string $slug):array;
    public function findToDoBySlug(string $slug):ToDoItem;
    public function findAllToDoItems(string $slug):array;


}