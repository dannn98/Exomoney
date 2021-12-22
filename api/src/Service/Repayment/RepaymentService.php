<?php


namespace App\Service\Repayment;


use App\DataObject\DataObjectAbstract;
use App\DataObject\RepaymentDataObject;
use App\Entity\Debt;
use App\Entity\Repayment;
use App\Entity\Team;
use App\EntityManager\Transaction;
use App\Exception\ApiException;
use App\Repository\RepaymentRepository;
use App\Service\Validator\ValidatorDTOInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
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

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function optimiseRepayments(Team $team): void
    {
        $users = array();
        $netChange = array();
        $newRepayments = array();

        //Pobranie danych z bazy
        $oldRepayments = $this->repaymentRepository->getRepaymentsFromTeam($team);
        if($oldRepayments === null) {
            return;
        }

        //Przygotowanie danych
        /** @var Repayment $repayment */
        foreach ($oldRepayments as $repayment) {
            $users[$repayment->getDebtor()->getId()] = $repayment->getDebtor();
            $users[$repayment->getCreditor()->getId()] = $repayment->getCreditor();
        }

        foreach ($users as $user) {
            $netChange[$user->getId()] = '0.00';

            /** @var Repayment $repayment */
            foreach ($oldRepayments as $repayment) {
                if ($repayment->getDebtor()->getId() === $user->getId()) {
                    $netChange[$user->getId()] = bcsub($netChange[$user->getId()], $repayment->getValue(), 2);
                    continue;
                }
                if ($repayment->getCreditor()->getId() === $user->getId()) {
                    $netChange[$user->getId()] = bcadd($netChange[$user->getId()], $repayment->getValue(), 2);
                }
            }
        }

        $netChange = array_filter($netChange, fn ($value) => $value !== '0.00');

        //Algorytm
        while(true) {
            asort($netChange);
            $repayment = new Repayment();
            $repayment->setTeam($team);

            foreach ($netChange as $key1 => $value1) {
                foreach ($netChange as $key2 => $value2) {
                    if($key1 === $key2) {
                        continue;
                    }
//                    dump($value1.' '.$value2);
                    if(bcmul($value1, '-1', 2) === $value2) {
                        $newRepayments[] = $repayment
                            ->setDebtor($users[$key1])
                            ->setCreditor($users[$key2])
                            ->setValue($value2);
                        unset($netChange[$key1]);
                        unset($netChange[$key2]);
                        break;
                    }
                }
            }

            if (empty($netChange)) {
                break;
            }

            switch (bccomp(bcmul(array_values($netChange)[0],'-1', 2), end($netChange), 2)) {
                case 1:
                    $newRepayments[] = $repayment
                        ->setDebtor($users[array_key_first($netChange)])
                        ->setCreditor($users[array_key_last($netChange)])
                        ->setValue(end($netChange));
                    $netChange[array_key_first($netChange)] = bcadd(array_values($netChange)[0], end($netChange), 2);
                    unset($netChange[array_key_last($netChange)]);
                    break;
                case 0:
                    $newRepayments[] = $repayment
                        ->setDebtor($users[array_key_first($netChange)])
                        ->setCreditor($users[array_key_last($netChange)])
                        ->setValue(end($netChange));
                    unset($netChange[array_key_first($netChange)]);
                    unset($netChange[array_key_last($netChange)]);
                    break;
                case -1:
                    $newRepayments[] = $repayment
                        ->setDebtor($users[array_key_first($netChange)])
                        ->setCreditor($users[array_key_last($netChange)])
                        ->setValue(bcmul(array_values($netChange)[0],'-1', 2));
                    $netChange[array_key_last($netChange)] = bcadd(array_values($netChange)[0], end($netChange), 2);
                    unset($netChange[array_key_first($netChange)]);
                    break;
            }
        }

        try {
            Transaction::beginTransaction();
            $this->repaymentRepository->removeCollection($oldRepayments);
            $this->repaymentRepository->saveCollection($newRepayments);
            Transaction::commit();
        } catch (OptimisticLockException | ORMException | Exception $e) {
            Transaction::rollback();
            throw $e;
        }
    }
}