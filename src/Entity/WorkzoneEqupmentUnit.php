<?php

namespace App\Entity;

use App\Repository\WorkzoneEqupmentUnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkzoneEqupmentUnitRepository::class)
 */
class WorkzoneEqupmentUnit
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isHide;

    /**
     * @ORM\OneToMany(targetEntity=WorkzoneEquipment::class, mappedBy="unit")
     */
    private $workzoneEquipment;

    public function __construct()
    {
        $this->workzoneEquipment = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isIsHide(): ?bool
    {
        return $this->isHide;
    }

    public function setIsHide(?bool $isHide): self
    {
        $this->isHide = $isHide;

        return $this;
    }

    /**
     * @return Collection<int, WorkzoneEquipment>
     */
    public function getWorkzoneEquipment(): Collection
    {
        return $this->workzoneEquipment;
    }

    public function addWorkzoneEquipment(WorkzoneEquipment $workzoneEquipment): self
    {
        if (!$this->workzoneEquipment->contains($workzoneEquipment)) {
            $this->workzoneEquipment[] = $workzoneEquipment;
            $workzoneEquipment->setUnit($this);
        }

        return $this;
    }

    public function removeWorkzoneEquipment(WorkzoneEquipment $workzoneEquipment): self
    {
        if ($this->workzoneEquipment->removeElement($workzoneEquipment)) {
            // set the owning side to null (unless already changed)
            if ($workzoneEquipment->getUnit() === $this) {
                $workzoneEquipment->setUnit(null);
            }
        }

        return $this;
    }
}
