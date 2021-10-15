<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    private UserPasswordEncoderInterface $authenticatedUser;

    private UserRepository $repo;

    public function __construct
    (
        UserPasswordEncoderInterface $authenticatedUser,
        UserRepository $userRepository
    )
    {
        $this->authenticatedUser = $authenticatedUser;
        $this->repo = $userRepository;
    }

    #[Route('/default', name: 'default')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DefaultController.php',
        ]);
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function registerUser()
    {
        $user = new User();
        $user   ->setEmail('dawid@gmail.com')
                ->setPassword($this->authenticatedUser->encodePassword($user, 'haslo'));

        $this->repo->save($user);

        return new Response("Chujj Ci w dupe");
    }
}
