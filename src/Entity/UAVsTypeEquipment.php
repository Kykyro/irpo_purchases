<?php

namespace App\Entity;

use App\Repository\UAVsTypeEquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UAVsTypeEquipmentRepository::class)
 */
class UAVsTypeEquipment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deliveredCount;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $deliveredSum;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $contractedCount;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $contractedSum;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $purchaseCount;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $purchaseSum;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $planCount;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $planSum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="uAVsTypeEquipment")
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $model;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mark;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveredCount(): ?int
    {
        return $this->deliveredCount;
    }

    public function setDeliveredCount(?int $deliveredCount): self
    {
        $this->deliveredCount = $deliveredCount;

        return $this;
    }

    public function getDeliveredSum(): ?string
    {
        return $this->deliveredSum;
    }

    public function setDeliveredSum(?string $deliveredSum): self
    {
        $this->deliveredSum = $deliveredSum;

        return $this;
    }

    public function getContractedCount(): ?int
    {
        return $this->contractedCount;
    }

    public function setContractedCount(?int $contractedCount): self
    {
        $this->contractedCount = $contractedCount;

        return $this;
    }

    public function getContractedSum(): ?string
    {
        return $this->contractedSum;
    }

    public function setContractedSum(?string $contractedSum): self
    {
        $this->contractedSum = $contractedSum;

        return $this;
    }

    public function getPurchaseCount(): ?int
    {
        return $this->purchaseCount;
    }

    public function setPurchaseCount(?int $purchaseCount): self
    {
        $this->purchaseCount = $purchaseCount;

        return $this;
    }

    public function getPurchaseSum(): ?string
    {
        return $this->purchaseSum;
    }

    public function setPurchaseSum(?string $purchaseSum): self
    {
        $this->purchaseSum = $purchaseSum;

        return $this;
    }

    public function getPlanCount(): ?int
    {
        return $this->planCount;
    }

    public function setPlanCount(?int $planCount): self
    {
        $this->planCount = $planCount;

        return $this;
    }

    public function getPlanSum(): ?string
    {
        return $this->planSum;
    }

    public function setPlanSum(?string $planSum): self
    {
        $this->planSum = $planSum;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function setMark(?string $mark): self
    {
        $this->mark = $mark;

        return $this;
    }
}
