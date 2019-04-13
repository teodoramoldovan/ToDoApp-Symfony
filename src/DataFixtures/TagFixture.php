<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->createMany(10, 'main_tags', function($i) {
            $tag=new Tag();
            $tag->setName($this->faker->realText(20));
            return $tag;
        });

        $manager->flush();
    }
}
