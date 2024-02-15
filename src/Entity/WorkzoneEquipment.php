<?php

namespace App\Entity;

use App\Repository\WorkzoneEquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkzoneEquipmentRepository::class)
 */
class WorkzoneEquipment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $specification;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $count;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $funds;

    /**
     * @ORM\ManyToOne(targetEntity=EquipmentType::class, inversedBy="workzoneEquipment")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=SheetWorkzone::class, inversedBy="workzoneEquipment")
     */
    private $sheet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zoneGroup;

    /**
     * @ORM\ManyToOne(targetEntity=WorkzoneEqupmentUnit::class, inversedBy="workzoneEquipment")
     */
    private $unit;

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

    public function getSpecification(): ?string
    {
        return $this->specification;
    }

    public function setSpecification(?string $specification): self
    {
        $this->specification = $specification;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getFunds(): ?string
    {
        return $this->funds;
    }

    public function setFunds(?string $funds): self
    {
        $this->funds = $funds;

        return $this;
    }

    public function getType(): ?EquipmentType
    {
        return $this->type;
    }

    public function setType(?EquipmentType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSheet(): ?SheetWorkzone
    {
        return $this->sheet;
    }

    public function setSheet(?SheetWorkzone $sheet): self
    {
        $this->sheet = $sheet;

        return $this;
    }

    public function getZoneGroup(): ?string
    {
        return $this->zoneGroup;
    }

    public function setZoneGroup(?string $zoneGroup): self
    {
        $this->zoneGroup = $zoneGroup;

        return $this;
    }

    public function getUnit(): ?WorkzoneEqupmentUnit
    {
        return $this->unit;
    }

    public function setUnit(?WorkzoneEqupmentUnit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }
}
