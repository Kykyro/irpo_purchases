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
     * @ORM\Column(type="text", nullable=true)
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $OKPD2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $KTRU;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $countryOfOrigin;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $model;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isHasModel;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $funds = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $inventoryNumber;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $sum;

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

    public function getOKPD2(): ?string
    {
        return $this->OKPD2;
    }

    public function setOKPD2(?string $OKPD2): self
    {
        $this->OKPD2 = $OKPD2;

        return $this;
    }

    public function getKTRU(): ?string
    {
        return $this->KTRU;
    }

    public function setKTRU(?string $KTRU): self
    {
        $this->KTRU = $KTRU;

        return $this;
    }

    public function getCountryOfOrigin(): ?string
    {
        return $this->countryOfOrigin;
    }

    public function setCountryOfOrigin(?string $countryOfOrigin): self
    {
        $this->countryOfOrigin = $countryOfOrigin;

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

    public function isIsHasModel(): ?bool
    {
        return $this->isHasModel;
    }

    public function setIsHasModel(?bool $isHasModel): self
    {
        $this->isHasModel = $isHasModel;

        return $this;
    }

    public function getFunds(): ?array
    {
        return $this->funds;
    }

    public function setFunds(?array $funds): self
    {
        $this->funds = $funds;

        return $this;
    }

    public function getInventoryNumber(): ?string
    {
        return $this->inventoryNumber;
    }

    public function setInventoryNumber(?string $inventoryNumber): self
    {
        $this->inventoryNumber = $inventoryNumber;

        return $this;
    }

    public function getSum(): ?string
    {
        return $this->sum;
    }

    public function setSum(?string $sum): self
    {
        $this->sum = $sum;

        return $this;
    }
}
