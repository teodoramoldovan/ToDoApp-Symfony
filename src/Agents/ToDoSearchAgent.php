<?php


namespace App\Agents;


use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ToDoSearchAgent
{
    private $toDoService;
    private $request;
    private $user;
    private $workspaceService;

    /**
     * ToDoSearchAgent constructor.
     * @param $toDoService
     * @param $request
     * @param $userId
     */
    public function __construct(ToDoItemApplicationService $toDoService,
                                RequestStack $requestStack,
                                TokenStorageInterface $tokenStorage,
                                WorkspaceApplicationService $workspaceService)
    {
        $this->toDoService = $toDoService;
        $this->request = $requestStack->getCurrentRequest();
        $this->user=$tokenStorage->getToken()->getUser();
        $this->workspaceService=$workspaceService;

    }

    public function getData(){

        $q=$this->request->query->get('q');
        $workspace=$this->workspaceService->findWorkspace($this->user->getId());

        $parts=explode("/",$this->request->getPathInfo());

        switch ($parts[3]){
            case "today":
                $toDoItems=$this->toDoService->findTodayToDos($q,$workspace->slug);
                break;
            case "upcoming":
                $toDoItems=$this->toDoService->findUpcomingToDos($q,$workspace->slug);
                break;
            case "logbook":
                $toDoItems=$this->toDoService->findCompletedToDos($q,$workspace->slug);
                break;
            case "anytime":
                $toDoItems=$this->toDoService->findAnytimeToDos($q,$workspace->slug);
                break;
            case "someday":
                $toDoItems=$this->toDoService->findSomedayToDos($q,$workspace->slug);
                break;
        }


        return $toDoItems;
    }


}