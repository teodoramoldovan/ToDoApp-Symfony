<?php


namespace App\Controller;


use App\ApplicationService\ProjectApplicationService;
use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use App\Repository\ProjectRepository;
use App\Repository\ToDoItemRepository;
use App\Repository\WorkspaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class WorkspaceController extends AbstractController
{


    /**
     * @Route("/workspace/{slug}",name="inbox_show")
     */
   public function showInbox(ProjectApplicationService $projectService,
                             WorkspaceApplicationService $workspaceService,
                             $slug){

       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       $user = $this->getUser();
       $userId=$user->getId();

       $workspace=$workspaceService->findWorkspace($userId);
       $projects=$projectService->findProjectsByWorkspace($slug);
      // $projects=$projectRepository->findProjectsByWorkspace($slug);
       return $this->render('workspace/inbox.html.twig',[
           'projects'=>$projects,
           'workspace'=>$workspace,


       ]);
   }
    /**
     * @Route("/workspace/{slug}/today", name="today_show")
     */
    public function showToday(WorkspaceApplicationService $workspaceService,
                              ToDoItemApplicationService $toDoService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$toDoService->findTodayToDos($workspace->slug);



        return $this->render('workspace/today.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,


        ]);

    }

    /**
     * @Route("/workspace/{slug}/upcoming", name="upcoming_show")
     */
    public function showUpcoming(WorkspaceApplicationService $workspaceService,
                                 ToDoItemApplicationService $toDoService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$toDoService->findUpcomingToDos($workspace->slug);



        return $this->render('workspace/upcoming.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,


        ]);

    }

    /**
     * @Route("/workspace/{slug}/logbook", name="logbook_show")
     */
    public function showLogbook(WorkspaceApplicationService $workspaceService,
                                ToDoItemApplicationService $toDoService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$toDoService->findCompletedToDos($workspace->slug);



        return $this->render('workspace/logbook.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,


        ]);

    }

    /**
     * @Route("/workspace/{slug}/anytime", name="anytime_show")
     */
    public function showAnytime(WorkspaceApplicationService $workspaceService,
                                ToDoItemApplicationService $toDoService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$toDoService->findAnytimeToDos($workspace->slug);

        $title="Anytime";

        return $this->render('workspace/anytime_someday.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,
            'title'=>$title,


        ]);

    }

    /**
     * @Route("/workspace/{slug}/someday", name="someday_show")
     */
    public function showSomeday(WorkspaceApplicationService $workspaceService,
                                ToDoItemApplicationService $toDoService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$toDoService->findSomedayToDos($workspace->slug);

        $title="Someday";

        return $this->render('workspace/anytime_someday.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,
            'title'=>$title,


        ]);

    }


}