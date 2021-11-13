<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private UserPasswordEncoderInterface $authenticatedUser;
    private UserRepository $userRepository;

    /**
     * @param UserPasswordEncoderInterface $authenticatedUser
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserPasswordEncoderInterface $authenticatedUser,
        UserRepository               $userRepository
    )
    {
        $this->authenticatedUser = $authenticatedUser;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function createUser(array $data): bool
    {
        $user = new User();
        $user->setEmail($data['email'])->setPassword($this->authenticatedUser->encodePassword($user, $data['password']));

        try {
            //TODO: Walidacja user
            $this->userRepository->save($user);
        } catch (OptimisticLockException | ORMException $e) {
            dump($e->getCode() . ": " . $e->getMessage());
            return false;
        }

        return true;
    }
}