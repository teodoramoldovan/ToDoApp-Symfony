<?php


namespace App\Controller;


use App\ApplicationService\ImportService;
use App\ApplicationService\ProjectApplicationService;
use App\ApplicationService\TagApplicationService;
use App\ApplicationService\ToDoItemApplicationService;
use App\ApplicationService\WorkspaceApplicationService;
use App\Entity\Project;
use App\Entity\Tag;
use App\Form\ToDoItemFormType;
use function MongoDB\BSON\toJSON;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ExportController extends BaseController
{
    /**
 * @Route("/workspace/{slug}/export",name="export")
 */
    public function exportData(ProjectApplicationService $projectService,
                               WorkspaceApplicationService $workspaceService,
                               $slug){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findWorkspace($userId);
        $projects=$projectService->findProjectsByWorkspace($slug);
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);


        $callback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ISO8601) : '';
        };
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'calendarDate' => $callback,
                'deadline' => $callback,
            ],
        ];
        //dd($defaultContext["callbacks"]["calendarDate"]);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = new ObjectNormalizer(null,null,null,
            null,null,null, $defaultContext);


        $serializer = new Serializer([$normalizers], $encoders);

        $jsonContent=$serializer->serialize($projects,'json', [
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },

            ObjectNormalizer::IGNORED_ATTRIBUTES=>['toDos','project','workspace','slug'],
        ]);

        //dd($jsonContent);
        $fs = new \Symfony\Component\Filesystem\Filesystem();

        try {
            $fs->dumpFile('C:\Users\user\Desktop\exported\exported.json', $jsonContent);
        }
        catch(IOException $e) {
            $this->addFlash('error', 'Data exported!');
        }

        $this->addFlash('success', 'Data exported!');
        return $this->redirectToRoute('app_homepage');
        /*return $this->render('workspace/inbox.html.twig',[
            'projects'=>$projects,
            'workspace'=>$workspace,
            'customWorkspaces'=>$customWorkspaces,


        ]);*/
    }
    /**
     * @Route("/workspace/{slug}/import",name="import")
     */
    public function importData(ProjectApplicationService $projectService,
                               WorkspaceApplicationService $workspaceService,
                               ImportService $importService,
                               $slug){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId=$user->getId();

        $workspace=$workspaceService->findSimpleWorkspace($userId);

        $importedData = file_get_contents('C:\Users\user\Desktop\exported\userdatauserpro.json');
        $data=json_decode($importedData);

        $importService->importData($data, $workspace);


        $this->addFlash('success', 'Data imported!');
        return $this->redirectToRoute('app_homepage');

    }

}