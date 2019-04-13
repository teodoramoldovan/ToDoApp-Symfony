<?php


namespace App\DataFixtures;


use App\Entity\Project;
use App\Repository\WorkspaceRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProjectFixtures extends BaseFixture implements DependentFixtureInterface
{
    private $workspaceRepository;
    private $workspaces;

    public function __construct(WorkspaceRepository $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }
    private static $projectNames = [
        'Vacation To Rome',
        'PS Assignment',
        'Mom\'s Surprise Bithday',
    ];

    protected function loadData(ObjectManager $manager)
    {

        $this->workspaces=$this->workspaceRepository->findAll();
        $this->createMany(40,'main_projects',function($count) use ($manager){
            $project=new Project();
            $project->setName($this->faker->randomElement(self::$projectNames))
                ->setDescription("Whatever description I want");
            $ws=$this->getRandomReference('main_workspaces0');
            $ws2=$this->getRandomReference('main_workspaces1');
            if($this->faker->boolean(70)){
                $project->setWorkspace($ws);
            }
            else {
                $project->setWorkspace($ws2);
            }

                ;
            return $project;
        });


        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            WorkspaceFixtures::class

        ];
    }


}