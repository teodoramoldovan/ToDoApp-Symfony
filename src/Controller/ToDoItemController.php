<?php


namespace App\Controller;


use App\ApplicationService\ProjectApplicationService;
use App\ApplicationService\TagApplicationService;
use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\ToDoItem;
use App\Form\ToDoItemFormType;
use Faker\Provider\DateTime;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use Pyrrah\Bundle\OpenWeatherMapBundle\Services\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);
        $toDoItems=$toDoService->findAllSearch($q,$workspace->slug);
        return $this->render('home/homepage.html.twig',[
            'workspace'=>$workspace,
            'toDoItems'=>$toDoItems,
            'customWorkspaces'=>$customWorkspaces,

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
                         WorkspaceApplicationService $workspaceService,
                         ToDoItemApplicationService $toDoService)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);
        $form = $this->createForm(ToDoItemFormType::class, $toDoItem, [
            'workspace'=>$workspace
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($toDoItem->getHeading()!=null){
                $date=new \DateTime();
                //$datee=date_format($date, 'Y-m-d');

                $toDoItem->setCalendarDate($date);
            }

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
            'customWorkspaces'=>$customWorkspaces
        ]);
    }

    /**
     * @Route("/toDoItem/{slug}/new", name="to_do_new")
     */
    public function add( Request $request,
                         WorkspaceApplicationService $workspaceService,
                         ToDoItemApplicationService $toDoService,
                            TagApplicationService $tagService)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);
        //dd($workspace);
        $form = $this->createForm(ToDoItemFormType::class,null, [
            'workspace'=>$workspace
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $toDoItem=$form->getData();
            $parts=$toDoService->extractTags($toDoItem->getName());
            if(!empty($parts)){
                $toDoItem->setName($parts[0]);
                foreach (array_slice($parts,1) as $part){
                    $tag=new Tag();
                    $tag->setName($part);
                    $tagService->addTag($tag);
                    $toDoItem->addTag($tag);
                }
            }
            else $toDoItem->setName($parts[0]);
            if($toDoItem->getHeading()!=null){
                $date=new \DateTime();
                $toDoItem->setCalendarDate($date);
            }
            $toDoService->addToDoItem($toDoItem);
            $this->addFlash('success', 'ToDoItem Created!');
            return $this->redirectToRoute('app_homepage');
        }
        return $this->render('to_do_operations/add.html.twig', [
            'toDoItemForm' => $form->createView(),
            'workspace'=>$workspace,
            'customWorkspaces'=>$customWorkspaces
        ]);
    }

    /**
     * @Route("/toDoItem/{slug}/convert", name="to_do_convert")
     */
    public function convert(ToDoItem $toDoItem,
                           ProjectApplicationService $projectService,
                            ToDoItemApplicationService $toDoService)
    {

        $project=$toDoItem->getProject();
        $workspace=$project->getWorkspace();

        $newProject=new Project();
        $newProject->setWorkspace($workspace)
            ->setDescription($toDoItem->getDescription())
            ->setName($toDoItem->getName())
            ;
        $projectService->addProject($newProject);
        foreach ($toDoItem->getCheckPoints() as $checkPoint){
            $newToDoItem=new ToDoItem();
            $newToDoItem->setProject($newProject)
                ->setName($checkPoint->getName());

            $toDoService->addToDoItem($newToDoItem);

            $newProject->addToDoItem($newToDoItem);
        }
        $this->delete($toDoItem,$toDoService);



        return new RedirectResponse('http://localhost:8000/workspace/'
            .$workspace);

    }


}