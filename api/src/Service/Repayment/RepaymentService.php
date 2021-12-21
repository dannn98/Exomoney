<?php


namespace App\Service\Repayment;


use App\DataObject\DataObjectAbstract;
use App\DataObject\RepaymentDataObject;
use App\Entity\Debt;
use App\Exception\ApiException;
use App\Repository\RepaymentRepository;
use App\Service\Validator\ValidatorDTOInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class RepaymentService implements RepaymentServiceInterface
{
    private ValidatorDTOInterface $validator;
    private RepaymentRepository $repaymentRepository;

    /**
     * @param ValidatorDTOInterface $validator
     * @param RepaymentRepository $repaymentRepository
     */
    public function __construct(ValidatorDTOInterface $validator, RepaymentRepository $repaymentRepository)
    {
        $this->validator = $validator;
        $this->repaymentRepository = $repaymentRepository;
    }

    /**
     * @param RepaymentDataObject $repaymentDTO
     * @param UserInterface $user
     *
     * @return bool
     * @throws ApiException
     */
    public function subtractFromRepayment(RepaymentDataObject $repaymentDTO, UserInterface $user): bool
    {
        $this->validator->validate($repaymentDTO);

        $repayment = $this->repaymentRepository->findOneBy(['uid' => $repaymentDTO->uid]);

        if($repayment === null) {
            throw new ApiException('Należność o podanym uid nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        if(!in_array($user, [$repayment->getCreditor(), $repayment->getDebtor()])) {
            throw new ApiException('Należność nie jest powiązana z użytkownikiem', statusCode: Response::HTTP_FORBIDDEN);
        }

        if($repayment->getValue() === null) {
            throw new ApiException('Należność o podanym uid nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        //TODO: Jeżeli użytkownik prześle więcej niż jest winien
        $res = bcsub($repayment->getValue(), number_format($repaymentDTO->value, 2), 2);
        if($res[0] === '-') {
            $repayment->setValue('0.00');
        }
        else {
            $repayment->setValue($res);
        }

        try {
            $this->repaymentRepository->save($repayment);
        } catch (OptimisticLockException | ORMException $e) {

        }

        return true;
    }

    public function optimiseRepayments()
    {
        // TODO: Implement optimiseRepayments() method.
    }
}