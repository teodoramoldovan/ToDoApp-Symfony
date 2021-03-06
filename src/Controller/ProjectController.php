<?php


namespace App\Controller;


use App\ApplicationService\ProjectApplicationService;
use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use App\Entity\ToDoItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{

    /**
     * @Route("/workspace/project/{slug}",name="project_show")
     */
    public function projectShow(ToDoItemApplicationService $toDoItemService,
                                ProjectApplicationService $projectService,
                                WorkspaceApplicationService $workspaceService,
                                Request $request,
                                $slug){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $q = $request->query->get('q');

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$toDoItemService->findToDosByProject($q,$slug);
        $project=$projectService->findProjectBySlug($slug);
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);


        return $this->render('workspace/project.html.twig',[
            'toDoItems'=>$toDoItems,
            'workspace'=>$workspace,
            'project'=>$project,
            'customWorkspaces'=>$customWorkspaces,


        ]);
    }


    /**
     * @Route("/workspace/project/{slug}/check",
     *      name="to_do_toggle_check",
     *      methods={"POST"})
     */
    public function toggleToDoCheck(ToDoItem $toDoItem,
                                    ToDoItemApplicationService $toDoItemService)
    {

        $toDoItemService->changeToDoDone($toDoItem);

        return new JsonResponse(['done'=>$toDoItem->getDone()]);
    }

}