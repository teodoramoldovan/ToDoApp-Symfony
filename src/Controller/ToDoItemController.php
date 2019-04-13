<?php


namespace App\Controller;


use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use App\Entity\ToDoItem;
use App\Form\ToDoItemFormType;
use App\Repository\ToDoItemRepository;
use App\Repository\WorkspaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ToDoItemController extends BaseController
{
    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage(WorkspaceApplicationService $workspaceService,
                             ToDoItemApplicationService $toDoService,
                             Request $request)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $q = $request->query->get('q');

        $workspace=$workspaceService->findWorkspace($userId);
        $toDoItems=$toDoService->findAllSearch($q,$workspace->slug);
        return $this->render('home/homepage.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,

        ]);
    }

    /**
     * @Route("/toDoItem/{slug}", name="to_do_delete")
     */
    public function delete(ToDoItem $toDoItem,
                           ToDoItemApplicationService $toDoService)
    {

        $project=$toDoItem->getProject();
        $projectSlug=$project->getSlug();

        $toDoService->delete($toDoItem);

        return new RedirectResponse('http://localhost:8000/workspace/project/'
                        .$projectSlug);

    }

    /**
     * @Route("/toDoItem/{slug}/edit", name="to_do_edit")
     */
    public function edit(ToDoItem $toDoItem, Request $request,
                         EntityManagerInterface $em,
                         WorkspaceApplicationService $workspaceService,
                         ToDoItemApplicationService $toDoService)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $form = $this->createForm(ToDoItemFormType::class, $toDoItem, [
            //'tags'=>$tagsArray,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $toDoService->editToDoItem($toDoItem);
            $this->addFlash('success', 'ToDoItem Updated!
                                Inaccuracies squashed!');
            return $this->redirectToRoute('to_do_edit', [
                'slug' => $toDoItem->getSlug(),
            ]);
        }
        return $this->render('to_do_operations/edit.html.twig', [
            'toDoItemForm' => $form->createView(),
            'workspace'=>$workspace,
        ]);
    }

}