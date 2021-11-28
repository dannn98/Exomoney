<?php

namespace App\Service\User;

use App\DataObject\UserDataObject;
use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\UserRepository;
use App\Service\Validator\ValidatorDTO;
use App\Service\Validator\ValidatorDTOInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService implements UserServiceInterface
{
    private ValidatorDTOInterface $validator;
    private UserPasswordEncoderInterface $authenticatedUser;
    private UserRepository $userRepository;

    /**
     * UserService constructor
     *
     * @param ValidatorDTOInterface $validator
     * @param UserPasswordEncoderInterface $authenticatedUser
     * @param UserRepository $userRepository
     */
    public function __construct(
        ValidatorDTOInterface $validator,
        UserPasswordEncoderInterface $authenticatedUser,
        UserRepository               $userRepository
    )
    {
        $this->validator = $validator;
        $this->authenticatedUser = $authenticatedUser;
        $this->userRepository = $userRepository;
    }

    /**
     * Create User
     *
     * @param UserDataObject $dto
     * @return bool
     * @throws ApiException
     */
    public function createUser(UserDataObject $dto): bool
    {
        $this->validator->validate($dto);

        $user = new User();
        $user->setEmail($dto->email);
        $user->setNickname($dto->nickname);
        $user->setPassword($this->authenticatedUser->encodePassword($user, $dto->password));

        try {
            $this->userRepository->save($user);
        } catch (OptimisticLockException | ORMException $e) {
            //TODO: Sprawdzić czy się duplikuje
        }

        return true;
    }
}