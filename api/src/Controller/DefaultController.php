<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function registerUser(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email'])->setPassword($this->authenticatedUser->encodePassword($user, $data['password']));

        $this->repo->save($user);

        return new Response("");
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function loginUser()
    {
        $user = new User();
        $user   ->setEmail()
                ->setPassword();
    }
}
