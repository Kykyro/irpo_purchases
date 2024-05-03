<?php

namespace App\Entity;

use App\Repository\ZoneGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneGroupRepository::class)
 */
class ZoneGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=SheetWorkzone::class, inversedBy="zoneGroups")
     */
    private $sheetWorkzone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $workplaces;

    /**
     * @ORM\OneToMany(targetEntity=WorkzoneEquipment::class, mappedBy="zoneGroup")
     */
    private $equipment;

    public function __construct()
    {
        $this->equipment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSheetWorkzone(): ?SheetWorkzone
    {
        return $this->sheetWorkzone;
    }

    public function setSheetWorkzone(?SheetWorkzone $sheetWorkzone): self
    {
        $this->sheetWorkzone = $sheetWorkzone;

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

    public function getWorkplaces(): ?int
    {
        return $this->workplaces;
    }

    public function setWorkplaces(?int $workplaces): self
    {
        $this->workplaces = $workplaces;

        return $this;
    }

    /**
     * @return Collection<int, WorkzoneEquipment>
     */
    public function getEquipment(): Collection
    {
        return $this->equipment;
    }

    public function addEquipment(WorkzoneEquipment $equipment): self
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment[] = $equipment;
            $equipment->setZoneGroup($this);
        }

        return $this;
    }

    public function removeEquipment(WorkzoneEquipment $equipment): self
    {
        if ($this->equipment->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getZoneGroup() === $this) {
                $equipment->setZoneGroup(null);
            }
        }

        return $this;
    }
}
