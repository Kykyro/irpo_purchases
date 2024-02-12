<?php

namespace App\Entity;

use App\Repository\EquipmentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipmentTypeRepository::class)
 */
class EquipmentType
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
     * @ORM\OneToMany(targetEntity=WorkzoneEquipment::class, mappedBy="type")
     */
    private $workzoneEquipment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isHide;

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
            $workzoneEquipment->setType($this);
        }

        return $this;
    }

    public function removeWorkzoneEquipment(WorkzoneEquipment $workzoneEquipment): self
    {
        if ($this->workzoneEquipment->removeElement($workzoneEquipment)) {
            // set the owning side to null (unless already changed)
            if ($workzoneEquipment->getType() === $this) {
                $workzoneEquipment->setType(null);
            }
        }

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
}
