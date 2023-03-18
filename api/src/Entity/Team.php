<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use App\Traits\CreatedAt;
use App\Traits\ModifiedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Team
{
    //Traits
    use CreatedAt;
    use ModifiedAt;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['Get_team_list', 'Get_team'])]
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['Get_team'])]
    private User $owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['Get_team_list', 'Get_team'])]
    private string $name;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    #[Groups(['Get_team_list', 'Get_team'])]
    private string $avatarUrl;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="teams")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=TeamAccessCode::class, mappedBy="team", fetch="EAGER")
     */
    private $teamAccessCodes;

    /**
     * @ORM\OneToMany(targetEntity=Debt::class, mappedBy="team", fetch="EAGER")
     */
    private $debts;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->teamAccessCodes = new ArrayCollection();
        $this->debts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(string $avatarUrl): void
    {
        $this->avatarUrl = $avatarUrl;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addTeam($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeTeam($this);
        }

        return $this;
    }

    public function getTeamAccessCodes(): Collection
    {
        return $this->teamAccessCodes;
    }

    public function addTeamAccessCode(TeamAccessCode $teamAccessCode): self
    {
        if (!$this->teamAccessCodes->contains($teamAccessCode)) {
            $this->teamAccessCodes[] = $teamAccessCode;
            $teamAccessCode->setTeam($this);
        }

        return $this;
    }

    public function removeTeamAccessCode(TeamAccessCode $teamAccessCode): self
    {
        if ($this->teamAccessCodes->removeElement($teamAccessCode)) {
            // set the owning side to null (unless already changed)
            if ($teamAccessCode->getTeam() === $this) {
                $teamAccessCode->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Debt[]
     */
    public function getDebts(): Collection
    {
        return $this->debts;
    }

    public function addDebt(Debt $debt): self
    {
        if (!$this->debts->contains($debt)) {
            $this->debts[] = $debt;
            $debt->setTeam($this);
        }

        return $this;
    }

    public function removeDebt(Debt $debt): self
    {
        if ($this->debts->removeElement($debt)) {
            // set the owning side to null (unless already changed)
            if ($debt->getTeam() === $this) {
                $debt->setTeam(null);
            }
        }

        return $this;
    }
}
