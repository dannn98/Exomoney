<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private UserPasswordEncoderInterface $authenticatedUser;
    private UserRepository $repo;

    public function __construct
    (
        UserPasswordEncoderInterface $authenticatedUser,
        UserRepository               $userRepository
    )
    {
        $this->authenticatedUser = $authenticatedUser;
        $this->repo = $userRepository;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function registerUser(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email'])->setPassword($this->authenticatedUser->encodePassword($user, $data['password']));

        $this->repo->save($user);

        return new Response(true, 200, []);
    }
}
