<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i) use ($manager){
            $user = new User();
            $user->setEmail(sprintf('organized%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->agreeToTerms();

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'organize'
            ));
            $apiToken1 = new ApiToken($user);
            $apiToken2 = new ApiToken($user);
            $manager->persist($apiToken1);
            $manager->persist($apiToken2);

            return $user;
        });

        $this->createMany(3, 'pro_users', function($i) {
            $user = new User();
            $user->setEmail(sprintf('pro%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->agreeToTerms();
            $user->setRoles(['ROLE_PRO']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'organize'
            ));
            return $user;
        });

        $manager->flush();
    }
}
