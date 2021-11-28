<?php

namespace App\Traits;

use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;

trait CreatedAt
{
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    #[Groups(['Get_debt_list'])]
    private DateTimeInterface $createdAt;

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @ORM\PrePersist
     * @throws Exception
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime(timezone:  new DateTimeZone('GMT+1'));
    }
}