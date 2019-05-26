<?php


namespace App\EventListener;

use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CalendarListener
{

    private $toDoService;
    private $request;
    private $user;
    private $workspaceService;
    public function __construct(ToDoItemApplicationService $toDoService,
                                RequestStack $requestStack,
                                TokenStorageInterface $tokenStorage,
                                WorkspaceApplicationService $workspaceService)
    {
        $this->toDoService=$toDoService;
        $this->user=$tokenStorage->getToken()->getUser();
        $this->workspaceService=$workspaceService;
        $this->request=$requestStack->getCurrentRequest();


    }

    public function load(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $workspace=$this->workspaceService->findWorkspace($this->user->getId());

        $toDoItems=$this->toDoService->findAllToDos($workspace->slug);

        foreach ($toDoItems as $toDoItem){
            $toDoEvent=new Event(
                $toDoItem->name,
                $toDoItem->calendarDate

            );

            $calendar->addEvent($toDoEvent);
        }


    }
}
