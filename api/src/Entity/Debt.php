<?php

namespace App\Entity;

use App\Repository\DebtRepository;
use App\Traits\CreatedAt;
use App\Traits\ModifiedAt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DebtRepository::class)
 * @ORM\HasLifecycleCallbacks()
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
    #[Groups(['Get_debt_list'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['Get_debt_list'])]
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="debts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['Get_debt_list'])]
    private $debtor;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['Get_debt_list'])]
    private $creditor;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    #[Groups(['Get_debt_list'])]
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
