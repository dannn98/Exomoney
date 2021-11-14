<?php

namespace App\Controller;

use App\Http\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/team', name: 'team.')]
class TeamController extends AbstractController
{
    #[Route(name: 'create', methods: ['POST'])]
    public function create(Request $request): ApiResponse
    {
        return new ApiResponse('Tworzenie zespołu');
    }
}
