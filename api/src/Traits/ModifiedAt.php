<?php

namespace App\Traits;

use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Exception;

trait ModifiedAt
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $modifiedAt;

    /**
     * @return ?DateTimeInterface
     */
    public function getModifiedAt(): ?DateTimeInterface
    {
        return $this->modifiedAt;
    }

    /**
     * @param DateTimeInterface $modifiedAt
     */
    public function setModifiedAt(DateTimeInterface $modifiedAt): void
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @ORM\PreUpdate
     * @throws Exception
     */
    public function onPreUpdate()
    {
        $this->modifiedAt = new \DateTime(timezone:  new DateTimeZone('GMT+1'));
    }
}