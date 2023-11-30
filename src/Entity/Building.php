<?php

namespace App\Entity;

use App\Repository\BuildingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BuildingRepository::class)
 */
class Building
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
     * @ORM\Column(type="float", nullable=true)
     */
    private $repairArea;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $neededFunds;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $possibleFunds;

    /**
     * @ORM\ManyToOne(targetEntity=BuildingType::class, inversedBy="buildings")
     */
    private $buildingType;

    /**
     * @ORM\ManyToOne(targetEntity=BuildingCategory::class, inversedBy="buildings")
     */
    private $buildingCategory;

    /**
     * @ORM\ManyToOne(targetEntity=BuildingPriority::class, inversedBy="buildings")
     */
    private $buildingPriority;

    /**
     * @ORM\ManyToOne(targetEntity=ProfEduOrg::class, inversedBy="buildings")
     */
    private $organization;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $Address;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $addFunds;

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

    public function getRepairArea(): ?float
    {
        return $this->repairArea;
    }

    public function setRepairArea(?float $repairArea): self
    {
        $this->repairArea = $repairArea;

        return $this;
    }

    public function getNeededFunds(): ?string
    {
        return $this->neededFunds;
    }

    public function setNeededFunds(?string $neededFunds): self
    {
        $this->neededFunds = $neededFunds;

        return $this;
    }

    public function getPossibleFunds(): ?string
    {
        return $this->possibleFunds;
    }

    public function setPossibleFunds(?string $possibleFunds): self
    {
        $this->possibleFunds = $possibleFunds;

        return $this;
    }

    public function getBuildingType(): ?BuildingType
    {
        return $this->buildingType;
    }

    public function setBuildingType(?BuildingType $buildingType): self
    {
        $this->buildingType = $buildingType;

        return $this;
    }

    public function getBuildingCategory(): ?BuildingCategory
    {
        return $this->buildingCategory;
    }

    public function setBuildingCategory(?BuildingCategory $buildingCategory): self
    {
        $this->buildingCategory = $buildingCategory;

        return $this;
    }

    public function getBuildingPriority(): ?BuildingPriority
    {
        return $this->buildingPriority;
    }

    public function setBuildingPriority(?BuildingPriority $buildingPriority): self
    {
        $this->buildingPriority = $buildingPriority;

        return $this;
    }

    public function getOrganization(): ?ProfEduOrg
    {
        return $this->organization;
    }

    public function setOrganization(?ProfEduOrg $organization): self
    {
        $this->organization = $organization;

        return $this;
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

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(?string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    public function getAddFunds(): ?string
    {
        return $this->addFunds;
    }

    public function setAddFunds(?string $addFunds): self
    {
        $this->addFunds = $addFunds;

        return $this;
    }
}
