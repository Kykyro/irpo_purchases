<?php

namespace App\Entity;

use App\Repository\BuildingTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BuildingTypeRepository::class)
 */
class BuildingType
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
     * @ORM\OneToMany(targetEntity=Building::class, mappedBy="buildingType")
     */
    private $buildings;

    public function __construct()
    {
        $this->buildings = new ArrayCollection();
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

    /**
     * @return Collection<int, Building>
     */
    public function getBuildings(): Collection
    {
        return $this->buildings;
    }

    public function addBuilding(Building $building): self
    {
        if (!$this->buildings->contains($building)) {
            $this->buildings[] = $building;
            $building->setBuildingType($this);
        }

        return $this;
    }

    public function removeBuilding(Building $building): self
    {
        if ($this->buildings->removeElement($building)) {
            // set the owning side to null (unless already changed)
            if ($building->getBuildingType() === $this) {
                $building->setBuildingType(null);
            }
        }

        return $this;
    }
}
