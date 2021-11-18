<?php

namespace App\Entity;

use App\Repository\TeamAccessCodeRepository;
use App\Traits\CreatedAt;
use App\Traits\ModifiedAt;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamAccessCodeRepository::class)
 */
class TeamAccessCode
{
    //Traits
    use CreatedAt;
    use ModifiedAt;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="teamAccessCodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private Team $team;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $code;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $numberOfUses = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $expireTime = null;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNumberOfUses(): ?int
    {
        return $this->numberOfUses;
    }

    public function setNumberOfUses(?int $numberOfUses): self
    {
        $this->numberOfUses = $numberOfUses;

        return $this;
    }

    public function getExpireTime(): ?DateTimeInterface
    {
        return $this->expireTime;
    }

    public function setExpireTime(?DateTimeInterface $expireTime): self
    {
        $this->expireTime = $expireTime;

        return $this;
    }
}
