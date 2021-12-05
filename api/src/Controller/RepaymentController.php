<?php

namespace App\Controller;

use App\Http\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/repayment', name: 'repayment.')]
class RepaymentController extends AbstractController
{
    #[Route(path: '/pay-off', name: 'pay-off', methods: ['POST'])]
    public function payOff(Request $request): ApiResponse
    {
        

        return new ApiResponse('Pomyślnie dokonano spłaty', status: Response::HTTP_OK);
    }
}
