<?php


namespace App\Service\Repayment;


use App\Entity\Debt;
use Symfony\Component\Security\Core\User\UserInterface;

interface RepaymentServiceInterface
{
    public function addToRepayment(Debt $debt);
    public function subtractFromRepayment(UserInterface $user);
    public function getRepaymentsAsDebtor(UserInterface $user);
    public function getRepaymentsAsCreditor(UserInterface $user);
    public function optimiseRepayments();
}