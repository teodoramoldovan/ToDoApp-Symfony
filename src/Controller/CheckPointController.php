<?php


namespace App\Controller;


use App\ApplicationService\CheckPointApplicationService;
use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use App\Entity\CheckPoint;
use App\Form\CheckPointFormType;
use App\Form\ToDoItemFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CheckPointController extends BaseController
{
    /**
     * @Route("/toDoItem/{slug}/checkpoint/new", name="check_point_new")
     */
    public function addCheckPoint( Request $request,
                         WorkspaceApplicationService $workspaceService,
                         ToDoItemApplicationService $toDoService,
                            CheckPointApplicationService $checkPointService,
                            $slug)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItem=$toDoService->findToDoBySlug($slug);
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);
        //dd($workspace);
        $form = $this->createForm(CheckPointFormType::class,null, [
            'workspace'=>$workspace,
            'toDoItem'=>$toDoItem,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $checkPoint=$form->getData();
            $checkPoint->setToDoItem($toDoItem);
            //dd($checkPoint);
            $checkPointService->addCheckPoint($checkPoint);
            $this->addFlash('success', 'CheckPoint Created!');
            return $this->redirectToRoute('check_point_new',array('slug'=>$slug));
        }
        return $this->render('checkpoint/add.html.twig', [
            'checkPointForm' => $form->createView(),
            'workspace'=>$workspace,
            'customWorkspaces'=>$customWorkspaces,

        ]);
    }

    /**
     * @Route("/workspace/project/toDoItem/checkpoint/{slug}/check",
     *      name="checkpoint_toggle_check",
     *      methods={"POST"})
     */
    public function toggleCheckPointCheck(CheckPoint $checkPoint,
                                    CheckPointApplicationService $checkPointService)
    {

        $checkPointService->changeCheckPointDone($checkPoint);

        return new JsonResponse(['done'=>$checkPoint->getDone()]);
    }

}