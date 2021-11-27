<?php

namespace App\Controller;

use App\DataObject\DebtDataObject;
use App\Http\ApiResponse;
use App\Service\DataObject\DataObjectServiceInterface;
use App\Service\Debt\DebtServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/debt', name: 'debt.')]
class DebtController extends AbstractController
{
    private DataObjectServiceInterface $dataObjectService;
    private DebtServiceInterface $debtService;

    /**
     * DebtController constructor
     *
     * @param DataObjectServiceInterface $dataObjectService
     * @param DebtServiceInterface $debtService
     */
    public function __construct(DataObjectServiceInterface $dataObjectService, DebtServiceInterface $debtService)
    {
        $this->dataObjectService = $dataObjectService;
        $this->debtService = $debtService;
    }

    /**
     * Add Debt
     *
     * @param Request $request
     *
     * @return ApiResponse
     */
    #[Route(name: 'add', methods: ['POST'])]
    public function add(Request $request): ApiResponse
    {
        $debtDTO = $this->dataObjectService->create($request, DebtDataObject::class);

        $this->debtService->addDebt($debtDTO, $this->getUser());

        return new ApiResponse('Pomyślnie utworzono dług', data: true, status: Response::HTTP_CREATED);
    }
}
