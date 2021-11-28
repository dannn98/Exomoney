<?php


namespace App\Service\Repayment;


use App\Entity\Debt;
use Symfony\Component\Security\Core\User\UserInterface;

class RepaymentService implements RepaymentServiceInterface
{

    public function addToRepayment(Debt $debt)
    {
        // TODO: Implement addToRepayment() method.
    }

    public function subtractFromRepayment(UserInterface $user)
    {
        // TODO: Implement subtractFromRepayment() method.
    }

    public function getRepaymentsAsDebtor(UserInterface $user)
    {
        // TODO: Implement getRepaymentsAsDebtor() method.
    }

    public function getRepaymentsAsCreditor(UserInterface $user)
    {
        // TODO: Implement getRepaymentsAsCreditor() method.
    }

    public function optimiseRepayments()
    {
        // TODO: Implement optimiseRepayments() method.
    }
}