<?php


namespace App\DataFixtures;


use App\Entity\Workspace;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class WorkspaceFixtures extends BaseFixture implements DependentFixtureInterface
{
    private $userRepository;
    private $id;
    //private $user;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    private static $workspaceNames = [
        'Inbox',
    ];

    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(10,'main_workspaces0',function($count) use ($manager){
            $workspace=new Workspace();
            $workspace->setName(self::$workspaceNames[0]);
            $referencesIndex=$this->getReferencesIndex('main_users');
            $randomReferenceKey=$this->faker->unique()->randomElement($referencesIndex);
            $user=$this->getReference($randomReferenceKey);
            $workspace->setUser($user);

            return $workspace;


        });
        $this->createMany(3,'main_workspaces1',function($count) use ($manager){
            $workspace=new Workspace();
            $workspace->setName(self::$workspaceNames[0]);
            $referencesIndex=$this->getReferencesIndex('pro_users');
            $randomReferenceKey=$this->faker->unique()->randomElement($referencesIndex);
            $user=$this->getReference($randomReferenceKey);
            $workspace->setUser($user);

            return $workspace;


        });


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }
}
