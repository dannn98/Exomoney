<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('default/default', name: 'default/default', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => 'Exomoney - praca inżynierska by Dawid Dąbek'], 200, []);
    }
}
