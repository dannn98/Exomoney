<?php

namespace App\Entity;

use App\Repository\DebtRepository;
use App\Traits\CreatedAt;
use App\Traits\ModifiedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DebtRepository::class)
 */
class Debt
{
    use CreatedAt;
    use ModifiedAt;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="debts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="debts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $debtor;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="credits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creditor;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
