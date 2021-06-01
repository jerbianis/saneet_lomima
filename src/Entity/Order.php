<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_price;

    /**
     * @ORM\OneToMany(targetEntity=Status::class, mappedBy="status_order", orphanRemoval=true)
     */
    private $order_status;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="orderr", orphanRemoval=true)
     */
    private $orderItems;

    public function __construct()
    {
        $this->order_status = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->total_price;
    }

    public function setTotalPrice(int $total_price): self
    {
        $this->total_price = $total_price;

        return $this;
    }

    /**
     * @return Collection|Status[]
     */
    public function getOrderStatus(): Collection
    {
        return $this->order_status;
    }

    public function addOrderStatus(Status $orderStatus): self
    {
        if (!$this->order_status->contains($orderStatus)) {
            $this->order_status[] = $orderStatus;
            $orderStatus->setStatusOrder($this);
        }

        return $this;
    }

    public function removeOrderStatus(Status $orderStatus): self
    {
        if ($this->order_status->removeElement($orderStatus)) {
            // set the owning side to null (unless already changed)
            if ($orderStatus->getStatusOrder() === $this) {
                $orderStatus->setStatusOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrderr($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrderr() === $this) {
                $orderItem->setOrderr(null);
            }
        }

        return $this;
    }
}
