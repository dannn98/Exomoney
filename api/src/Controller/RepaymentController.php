<?php

namespace App\Controller;

use App\DataObject\RepaymentDataObject;
use App\Http\ApiResponse;
use App\Service\DataObject\DataObjectServiceInterface;
use App\Service\Repayment\RepaymentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/repayment', name: 'repayment.')]
class RepaymentController extends AbstractController
{
    private DataObjectServiceInterface $dataObjectService;
    private RepaymentServiceInterface $repaymentService;

    /**
     * @param DataObjectServiceInterface $dataObjectService
     * @param RepaymentServiceInterface $repaymentService
     */
    public function __construct(DataObjectServiceInterface $dataObjectService, RepaymentServiceInterface $repaymentService)
    {
        $this->dataObjectService = $dataObjectService;
        $this->repaymentService = $repaymentService;
    }

    #[Route(path: '/pay-off', name: 'pay-off', methods: ['POST'])]
    public function payOff(Request $request): ApiResponse
    {
        $repaymentDTO = $this->dataObjectService->create($request, RepaymentDataObject::class);

        $this->repaymentService->subtractFromRepayment($repaymentDTO, $this->getUser());

        return new ApiResponse('Pomyślnie dokonano spłaty', status: Response::HTTP_OK);
    }
}
