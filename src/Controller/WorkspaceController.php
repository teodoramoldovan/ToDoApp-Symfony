<?php


namespace App\Controller;


use App\ApplicationService\ProjectApplicationService;
use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use App\Form\WorkspaceFormType;
use App\Framework\AgentFramework;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
       $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);
      // $projects=$projectRepository->findProjectsByWorkspace($slug);
       return $this->render('workspace/inbox.html.twig',[
           'projects'=>$projects,
           'workspace'=>$workspace,
           'customWorkspaces'=>$customWorkspaces,


       ]);
   }
    /**
     * @Route("/workspace/{slug}/today", name="today_show")
     */
    public function showToday(WorkspaceApplicationService $workspaceService,
                              AgentFramework $framework)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);

        $weather=$framework->receiveIntent("weather","display");
        $toDoItems=$framework->receiveIntent("toDo","search");


        return $this->render('workspace/today.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,
            'customWorkspaces'=>$customWorkspaces,
            'weather'=>$weather,


        ]);

    }

    /**
     * @Route("/workspace/{slug}/upcoming", name="upcoming_show")
     */
    public function showUpcoming(WorkspaceApplicationService $workspaceService,
                                 AgentFramework $framework)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$framework->receiveIntent("toDo","search");
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);




        return $this->render('workspace/upcoming.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,
            'customWorkspaces'=>$customWorkspaces,


        ]);

    }

    /**
     * @Route("/workspace/{slug}/logbook", name="logbook_show")
     */
    public function showLogbook(WorkspaceApplicationService $workspaceService,
                                AgentFramework $framework)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();


        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$framework->receiveIntent("toDo","search");
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);



        return $this->render('workspace/logbook.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,
            'customWorkspaces'=>$customWorkspaces,


        ]);

    }

    /**
     * @Route("/workspace/{slug}/anytime", name="anytime_show")
     */
    public function showAnytime(WorkspaceApplicationService $workspaceService,
                                AgentFramework $framework)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();


        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$framework->receiveIntent("toDo","search");
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);

        $title="Anytime";

        return $this->render('workspace/anytime_someday.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,
            'title'=>$title,
            'customWorkspaces'=>$customWorkspaces,


        ]);

    }

    /**
     * @Route("/workspace/{slug}/someday", name="someday_show")
     */
    public function showSomeday(WorkspaceApplicationService $workspaceService,
                                AgentFramework $framework)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();



        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$framework->receiveIntent("toDo","search");
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);

        $title="Someday";

        return $this->render('workspace/anytime_someday.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,
            'title'=>$title,
            'customWorkspaces'=>$customWorkspaces,


        ]);

    }

    /**
     * @Route("/workspace/{slug}/new", name="workspace_new")
     */
    public function add( Request $request,
                         WorkspaceApplicationService $workspaceService,
                         ToDoItemApplicationService $toDoService)
    {


        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);

        $form = $this->createForm(WorkspaceFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newWorkspace=$form->getData();
            $newWorkspace->setUser($user);
            $workspaceService->addWorkspace($newWorkspace);
            $this->addFlash('success', 'Workspace Created!');
            return $this->redirectToRoute('app_homepage');
        }
        return $this->render('workspace/add.html.twig', [
            'workspaceForm' => $form->createView(),
            'workspace'=>$workspace,
            'customWorkspaces'=>$customWorkspaces,
        ]);
    }


}