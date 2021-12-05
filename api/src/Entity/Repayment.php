<?php

namespace App\Entity;

use App\Repository\RepaymentRepository;
use App\Traits\CreatedAt;
use App\Traits\ModifiedAt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RepaymentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Repayment
{
    use CreatedAt;
    use ModifiedAt;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="debtsFromRepayments")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['Get_repayment_list'])]
    private $debtor;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="creditsFromRepayments")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['Get_repayment_list'])]
    private $creditor;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    #[Groups(['Get_repayment_list'])]
    private $value;

    public function getDebtor(): ?User
    {
        return $this->debtor;
    }

    public function setDebtor(?User $debtor): self
    {
        $this->debtor = $debtor;

        return $this;
    }

    public function getCreditor(): ?User
    {
        return $this->creditor;
    }

    public function setCreditor(?User $creditor): self
    {
        $this->creditor = $creditor;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
