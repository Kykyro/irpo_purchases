<?php

namespace App\Entity;

use App\Repository\ZoneRequirementsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneRequirementsRepository::class)
 */
class ZoneRequirements
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $area;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lighting;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $internet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $electricity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $groundLoop;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $floor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $water;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $compressedAir;

    /**
     * @ORM\OneToOne(targetEntity=SheetWorkzone::class, inversedBy="zoneRequirements", cascade={"persist", "remove"})
     */
    private $zone;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $workplaceCount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(?float $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getLighting(): ?string
    {
        return $this->lighting;
    }

    public function setLighting(?string $lighting): self
    {
        $this->lighting = $lighting;

        return $this;
    }

    public function getInternet(): ?string
    {
        return $this->internet;
    }

    public function setInternet(?string $internet): self
    {
        $this->internet = $internet;

        return $this;
    }

    public function getElectricity(): ?string
    {
        return $this->electricity;
    }

    public function setElectricity(?string $electricity): self
    {
        $this->electricity = $electricity;

        return $this;
    }

    public function getGroundLoop(): ?string
    {
        return $this->groundLoop;
    }

    public function setGroundLoop(?string $groundLoop): self
    {
        $this->groundLoop = $groundLoop;

        return $this;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function setFloor(?string $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getWater(): ?string
    {
        return $this->water;
    }

    public function setWater(?string $water): self
    {
        $this->water = $water;

        return $this;
    }

    public function getCompressedAir(): ?string
    {
        return $this->compressedAir;
    }

    public function setCompressedAir(?string $compressedAir): self
    {
        $this->compressedAir = $compressedAir;

        return $this;
    }

    public function getZone(): ?SheetWorkzone
    {
        return $this->zone;
    }

    public function setZone(?SheetWorkzone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getWorkplaceCount(): ?int
    {
        return $this->workplaceCount;
    }

    public function setWorkplaceCount(?int $workplaceCount): self
    {
        $this->workplaceCount = $workplaceCount;

        return $this;
    }
}
