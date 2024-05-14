<?php

namespace App\Entity;

use App\Repository\WorkzoneEquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=WorkzoneEquipmentRepository::class)
 */
class WorkzoneEquipment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("equipment_json")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Groups("equipment_json")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("equipment_json")
     */
    private $specification;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("equipment_json")
     */
    private $count;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("equipment_json")
     */
    private $funds;

    /**
     * @ORM\OneToMany(targetEntity=EquipmentLog::class, mappedBy="equipment")
     *
     */
    private $equipmentLogs;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("equipment_json")
     */
    private $done;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("equipment_json")
     */
    private $clusterComment;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("equipment_json")
     */
    private $curatorComment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("equipment_json")
     */
    private $deleted;

    /**
     * @ORM\OneToMany(targetEntity=WorkzoneEquipmentDump::class, mappedBy="equipment")
     */
    private $workzoneEquipmentDumps;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("equipment_json")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("equipment_json")
     */
    private $unit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("equipment_json")
     */
    private $workplaceNum;

    /**
     * @ORM\ManyToOne(targetEntity=ZoneGroup::class, inversedBy="equipment")
     */
    private $zoneGroup;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getWorkplaceNum(): ?int
    {
        return $this->workplaceNum;
    }

    public function setWorkplaceNum(?int $workplaceNum): self
    {
        $this->workplaceNum = $workplaceNum;

        return $this;
    }

    public function getZoneGroup(): ?ZoneGroup
    {
        return $this->zoneGroup;
    }

    public function setZoneGroup(?ZoneGroup $zoneGroup): self
    {
        $this->zoneGroup = $zoneGroup;

        return $this;
    }
}
