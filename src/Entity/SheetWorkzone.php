<?php

namespace App\Entity;

use App\Repository\SheetWorkzoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SheetWorkzoneRepository::class)
 */
class SheetWorkzone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sheetWorkzones")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=ZoneRequirements::class, mappedBy="zone", cascade={"persist", "remove"})
     */
    private $zoneRequirements;

    /**
     * @ORM\OneToMany(targetEntity=ZoneGroup::class, mappedBy="sheetWorkzone")
     */
    private $zoneGroups;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $FGOS = [];


    function __construct($user)
    {
        $this->setUser($user);
        $this->setZoneRequirements(new ZoneRequirements());
        $this->workzoneEquipment = new ArrayCollection();
        $this->zoneGroups = new ArrayCollection();
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


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getZoneRequirements(): ?ZoneRequirements
    {
        return $this->zoneRequirements;
    }

    public function setZoneRequirements(?ZoneRequirements $zoneRequirements): self
    {
        // unset the owning side of the relation if necessary
        if ($zoneRequirements === null && $this->zoneRequirements !== null) {
            $this->zoneRequirements->setZone(null);
        }

        // set the owning side of the relation if necessary
        if ($zoneRequirements !== null && $zoneRequirements->getZone() !== $this) {
            $zoneRequirements->setZone($this);
        }

        $this->zoneRequirements = $zoneRequirements;

        return $this;
    }

    /**
     * @return Collection<int, ZoneGroup>
     */
    public function getZoneGroups(): Collection
    {
        return $this->zoneGroups;
    }
    /**
     * @return Collection<int, ZoneGroup>
     */
    public function getZoneGroupsSorted(): Collection
    {
        $criteria = Criteria::create()
            ->orderBy(['workplaces' => 'ASC'])
        ;
        return $this->zoneGroups->matching($criteria);


        return $this->zoneGroups;
    }

    public function addZoneGroup(ZoneGroup $zoneGroup): self
    {
        if (!$this->zoneGroups->contains($zoneGroup)) {
            $this->zoneGroups[] = $zoneGroup;
            $zoneGroup->setSheetWorkzone($this);
        }

        return $this;
    }

    public function removeZoneGroup(ZoneGroup $zoneGroup): self
    {
        if ($this->zoneGroups->removeElement($zoneGroup)) {
            // set the owning side to null (unless already changed)
            if ($zoneGroup->getSheetWorkzone() === $this) {
                $zoneGroup->setSheetWorkzone(null);
            }
        }

        return $this;
    }

    public function getFGOS(): ?array
    {
        return $this->FGOS;
    }

    public function setFGOS(?array $FGOS): self
    {
        $this->FGOS = $FGOS;

        return $this;
    }



}
