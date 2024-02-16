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
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $FGOS;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sheetWorkzones")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=ZoneRequirements::class, mappedBy="zone", cascade={"persist", "remove"})
     */
    private $zoneRequirements;

    /**
     * @ORM\OneToMany(targetEntity=WorkzoneEquipment::class, mappedBy="sheet")
     */
    private $workzoneEquipment;



    function __construct($user)
    {
        $this->setUser($user);
        $this->setZoneRequirements(new ZoneRequirements());
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

    public function getFGOS(): ?string
    {
        return $this->FGOS;
    }

    public function setFGOS(?string $FGOS): self
    {
        $this->FGOS = $FGOS;

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
            $workzoneEquipment->setSheet($this);
        }

        return $this;
    }

    public function removeWorkzoneEquipment(WorkzoneEquipment $workzoneEquipment): self
    {
        if ($this->workzoneEquipment->removeElement($workzoneEquipment)) {
            // set the owning side to null (unless already changed)
            if ($workzoneEquipment->getSheet() === $this) {
                $workzoneEquipment->setSheet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WorkzoneEquipment>
     */
    public function getWorkzoneEquipmentByType($type)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('zoneGroup', $type))
        ;

        return ['workzoneEquipment' => $this->workzoneEquipment->matching($criteria)];
    }

    public function getWorkzoneEquipmentByGroup()
    {
        $criteria = Criteria::create()
            ->orderBy(['zoneGroup' => 'DESC']);

        $equipment = $this->workzoneEquipment->matching($criteria);
        $arr = ['Общая зона' => [],
            'Рабочее место преподавателя' => [],
            'Рабочее место учащегося' => [],
            'Охрана труда и техника безопасности' => []
        ];

        foreach ($equipment as $i)
        {
            $group = $i->getZoneGroup();
            if($group)
            {
                if(!array_key_exists($group,$arr))
                    $arr[$group] = [];
                array_push($arr[$group], $i);
            }
        }



        return  $arr;
    }

}
