<?php

namespace App\Controller;

use App\Exception\ApiException;
use App\Fixtures\FixturesService;
use App\Http\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/default', name: 'default.')]
class DefaultController extends AbstractController
{
    private FixturesService $fs;

    /**
     * @param FixturesService $fs
     */
    public function __construct(FixturesService $fs)
    {
        $this->fs = $fs;
    }

    /**
     * @return JsonResponse
     */
    #[Route(name: 'default', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $this->fs->generate();

        return new ApiResponse('Wszystko ok');
    }
}
