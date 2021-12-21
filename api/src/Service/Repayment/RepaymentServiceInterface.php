<?php


namespace App\Service\Repayment;


use App\DataObject\RepaymentDataObject;
use App\Entity\Team;
use Symfony\Component\Security\Core\User\UserInterface;

interface RepaymentServiceInterface
{
    public function subtractFromRepayment(RepaymentDataObject $repaymentDTO, UserInterface $user): bool;
    public function optimiseRepayments(Team $team): void;
}