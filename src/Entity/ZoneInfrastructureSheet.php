<?php

namespace App\Entity;

use App\Repository\ZoneInfrastructureSheetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneInfrastructureSheetRepository::class)
 */
class ZoneInfrastructureSheet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalNumber;

    /**
     * @ORM\ManyToOne(targetEntity=ClusterZone::class, inversedBy="zoneInfrastructureSheets")
     */
    private $zone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $units;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $factNumber;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $deliveryDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zoneType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $putIntoOperation;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTotalNumber(): ?int
    {
        return $this->totalNumber;
    }

    public function setTotalNumber(int $totalNumber): self
    {
        $this->totalNumber = $totalNumber;

        return $this;
    }

    public function getZone(): ?ClusterZone
    {
        return $this->zone;
    }

    public function setZone(?ClusterZone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUnits(): ?string
    {
        return $this->units;
    }

    public function setUnits(?string $units): self
    {
        $this->units = $units;

        return $this;
    }

    public function getFactNumber(): ?int
    {
        return $this->factNumber;
    }

    public function setFactNumber(?int $factNumber): self
    {
        $this->factNumber = $factNumber;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(?\DateTimeInterface $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getZoneType(): ?string
    {
        return $this->zoneType;
    }

    public function setZoneType(?string $zoneType): self
    {
        $this->zoneType = $zoneType;

        return $this;
    }

    public function getPutIntoOperation(): ?int
    {
        return $this->putIntoOperation;
    }

    public function setPutIntoOperation(?int $putIntoOperation): self
    {
        $this->putIntoOperation = $putIntoOperation;

        return $this;
    }
}
