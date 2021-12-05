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
    /**
     * @return JsonResponse
     */
    #[Route(name: 'default', methods: ['GET'])]
    public function index(): JsonResponse
    {
//        $x = new FixturesService();
//        $x->generate();
//        return new ApiResponse('Siema', errors: ['siema' => ['elo']]);

        $val1 = strval(10.09);
        $val2 = "5.01";
        dd(bcadd($val1, $val2, 2));

        return new ApiResponse('Wszystko ok');
    }
}
