<?php

namespace App\Entity;

use App\Repository\WorkzoneEquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=EquipmentLog::class, mappedBy="equipment")
     */
    private $equipmentLogs;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $done;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $clusterComment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $curatorComment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\OneToMany(targetEntity=WorkzoneEquipmentDump::class, mappedBy="equipment")
     */
    private $workzoneEquipmentDumps;

    public function __construct()
    {
        $this->equipmentLogs = new ArrayCollection();
        $this->workzoneEquipmentDumps = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, EquipmentLog>
     */
    public function getEquipmentLogs(): Collection
    {
        return $this->equipmentLogs;
    }

    public function addEquipmentLog(EquipmentLog $equipmentLog): self
    {
        if (!$this->equipmentLogs->contains($equipmentLog)) {
            $this->equipmentLogs[] = $equipmentLog;
            $equipmentLog->setEquipment($this);
        }

        return $this;
    }

    public function removeEquipmentLog(EquipmentLog $equipmentLog): self
    {
        if ($this->equipmentLogs->removeElement($equipmentLog)) {
            // set the owning side to null (unless already changed)
            if ($equipmentLog->getEquipment() === $this) {
                $equipmentLog->setEquipment(null);
            }
        }

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(?bool $done): self
    {
        $this->done = $done;

        return $this;
    }

    public function getClusterComment(): ?string
    {
        return $this->clusterComment;
    }

    public function setClusterComment(?string $clusterComment): self
    {
        $this->clusterComment = $clusterComment;

        return $this;
    }

    public function getCuratorComment(): ?string
    {
        return $this->curatorComment;
    }

    public function setCuratorComment(?string $curatorComment): self
    {
        $this->curatorComment = $curatorComment;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return Collection<int, WorkzoneEquipmentDump>
     */
    public function getWorkzoneEquipmentDumps(): Collection
    {
        return $this->workzoneEquipmentDumps;
    }

    public function addWorkzoneEquipmentDump(WorkzoneEquipmentDump $workzoneEquipmentDump): self
    {
        if (!$this->workzoneEquipmentDumps->contains($workzoneEquipmentDump)) {
            $this->workzoneEquipmentDumps[] = $workzoneEquipmentDump;
            $workzoneEquipmentDump->setEquipment($this);
        }

        return $this;
    }

    public function removeWorkzoneEquipmentDump(WorkzoneEquipmentDump $workzoneEquipmentDump): self
    {
        if ($this->workzoneEquipmentDumps->removeElement($workzoneEquipmentDump)) {
            // set the owning side to null (unless already changed)
            if ($workzoneEquipmentDump->getEquipment() === $this) {
                $workzoneEquipmentDump->setEquipment(null);
            }
        }

        return $this;
    }
}
