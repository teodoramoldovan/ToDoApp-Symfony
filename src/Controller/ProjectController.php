<?php


namespace App\Controller;


use App\ApplicationService\ProjectApplicationService;
use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use App\Domain\Model\ToDoItemDTO;
use App\Entity\ToDoItem;
use App\Repository\ProjectRepository;
use App\Repository\ToDoItemRepository;
use App\Repository\WorkspaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{

    /**
     * @Route("/workspace/project/{slug}",name="project_show")
     */
    public function projectShow(ToDoItemApplicationService $toDoItemService,
                                ProjectApplicationService $projectService,
                                WorkspaceApplicationService $workspaceService,
                                $slug){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$toDoItemService->findToDosByProject($slug);
        $project=$projectService->findProjectBySlug($slug);


        return $this->render('workspace/project.html.twig',[
            'toDoItems'=>$toDoItems,
            'workspace'=>$workspace,
            'project'=>$project,


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