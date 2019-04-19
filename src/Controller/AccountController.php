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
class AccountController extends BaseController
{

    /**
     * @Route("/account", name="app_account")
     */
    public function index(LoggerInterface $logger,
                          WorkspaceApplicationService $workspaceService)
    {

        $user = $this->getUser();
        $userId=$user->getId();



        $workspace=$workspaceService->findWorkspace($userId);
        $customWorkspaces=$workspaceService->findCustomWorkspaces($userId);

        $logger->debug('Checking account page for '
                            .$this->getUser()->getEmail());
        return $this->render('account/index.html.twig', [
            'workspace'=>$workspace,
            'customWorkspaces'=>$customWorkspaces,
        ]);
    }
    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountApi()
    {
        $user = $this->getUser();
        return $this->json($user, 200, [], [
            'groups' => ['main'],
        ]);
    }
}
