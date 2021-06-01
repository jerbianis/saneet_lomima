<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Exception\InvalidArgumentException;

/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $status_at;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="order_status")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status_order;

    private const STATUS_LEVEL=['New','Confirmed','Canceled','Shipped','Delivered'];
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, self::STATUS_LEVEL)) {
            throw new InvalidArgumentException('Status must be one of '
                . implode(', ', self::STATUS_LEVEL));
        }
        $this->status = $status;

        return $this;
    }

    public function getStatusAt(): ?\DateTimeInterface
    {
        return $this->status_at;
    }

    public function setStatusAt(\DateTimeInterface $status_at): self
    {
        $this->status_at = $status_at;

        return $this;
    }

    public function getStatusOrder(): ?Order
    {
        return $this->status_order;
    }

    public function setStatusOrder(?Order $status_order): self
    {
        $this->status_order = $status_order;

        return $this;
    }
}
