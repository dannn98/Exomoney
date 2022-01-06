<?php

namespace App\Fixtures\Processors;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProcessor
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function preProcess(User $object): void
    {
        $object->setPassword($this->userPasswordEncoder->encodePassword($object, $object->getPassword()));
    }
}