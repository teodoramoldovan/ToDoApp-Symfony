<?php


namespace App\ApplicationService;


use App\Domain\UserRepositoryInterface;
use App\Entity\User;
use App\Entity\Workspace;
use App\Form\Model\UserRegistrationFormModel;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserApplicationService
{
    private $userRepository;
    public function __construct(UserRepositoryInterface
                                $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function registerUser(UserRegistrationFormModel
                                 $userModel,
                                 UserPasswordEncoderInterface
                                 $passwordEncoder)
    {
        $user = new User();
        $user->setEmail($userModel->email);
        $user->setPassword($passwordEncoder->encodePassword(
            $user,
            $userModel->plainPassword
        ));

        if (true === $userModel->agreeTerms) {
            $user->agreeToTerms();
        }
        $workspace=new Workspace();
        $workspace->setUser($user)
            ->setName("Inbox");

        $this->userRepository->insertUser($user,$workspace);

        return $user;



    }

}