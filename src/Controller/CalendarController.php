<?php

namespace App\Controller;


use App\ApplicationService\WorkspaceApplicationService;
use App\Domain\Service\WorkspaceService;
use App\Repository\WorkspaceRepository;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @IsGranted("ROLE_USER")
 */
class CalendarController extends BaseController
{

    /**
     * @Route("/calendar", name="app_account")
     */
    public function index(LoggerInterface $logger,
                          WorkspaceApplicationService $workspaceService)
    {

        $user = $this->getUser();
        $userId=$user->getId();



        $workspace=$workspaceService->findWorkspace($userId);
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);

        $logger->debug('Checking calendar page for '
                            .$this->getUser()->getEmail());
        return $this->render('calendar/index.html.twig', [
            'workspace'=>$workspace,
            'customWorkspaces'=>$customWorkspaces,
        ]);
    }
    /**
     * @Route("/api/calendar", name="api_account")
     */
    public function accountApi()
    {
        $user = $this->getUser();
        return $this->json($user, 200, [], [
            'groups' => ['main'],
        ]);
    }
}
