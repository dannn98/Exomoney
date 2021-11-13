<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/default', name: 'default.')]
class DefaultController extends AbstractController
{
    /**
     * @return JsonResponse
     */
    #[Route(name: 'default', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => 'Exomoney - praca inżynierska by Dawid Dąbek'], Response::HTTP_OK, []);
    }
}
