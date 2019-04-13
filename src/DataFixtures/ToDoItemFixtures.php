<?php


namespace App\DataFixtures;


use App\Entity\ToDoItem;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ToDoItemFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $toDoItemNames = [
        'Book Flights',
        'Read about metro',
        'Borrow Sarah\'s travel guide',
    ];

    protected function loadData(ObjectManager $manager)
    {


        $this->createMany(200,'main_todoitems',function($count) use ($manager){
            $toDoItem=new ToDoItem();
            $toDoItem->setName($this->faker->randomElement(self::$toDoItemNames));





            if($this->faker->boolean(70)){
                $toDoItem->setCalendarDate($this->faker->dateTimeBetween('-2 days', '+10 days'));


            }
            else if($this->faker->boolean(40)){
                $toDoItem->setWish(true);
            }


            $toDoItem->setProject($this->getRandomReference('main_projects'));

            $tags = $this->getRandomReferences('main_tags', $this->faker->numberBetween(0, 5));
            foreach ($tags as $tag) {
                $toDoItem->addTag($tag);
            }
            $toDoItem->setDone(false);
            return $toDoItem;


        });


        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            TagFixture::class,
            UserFixture::class,
            ProjectFixtures::class,
        ];
    }

}