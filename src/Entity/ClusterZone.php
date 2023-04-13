<?php

namespace App\Entity;

use App\Repository\ClusterZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ZoneRepair;

/**
 * @ORM\Entity(repositoryClass=ClusterZoneRepository::class)
 */
class ClusterZone
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
     * @ORM\ManyToOne(targetEntity=ClusterAddresses::class, inversedBy="clusterZones")
     */
    private $addres;

    /**
     * @ORM\OneToOne(targetEntity=ZoneRepair::class, mappedBy="clusterZone", cascade={"persist", "remove"})
     */
    private $zoneRepair;

    /**
     * @ORM\OneToMany(targetEntity=ZoneInfrastructureSheet::class, mappedBy="zone")
     */
    private $zoneInfrastructureSheets;

    /**
     * @ORM\ManyToOne(targetEntity=ZoneType::class, inversedBy="clusterZones")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $doNotTake;

    function __construct() {
        $this->setZoneRepair(new ZoneRepair());
        $this->zoneInfrastructureSheets = new ArrayCollection();
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

    public function getAddres(): ?ClusterAddresses
    {
        return $this->addres;
    }

    public function setAddres(?ClusterAddresses $addres): self
    {
        $this->addres = $addres;

        return $this;
    }

    public function getZoneRepair(): ?ZoneRepair
    {
        return $this->zoneRepair;
    }

    public function setZoneRepair(?ZoneRepair $zoneRepair): self
    {
        // unset the owning side of the relation if necessary
        if ($zoneRepair === null && $this->zoneRepair !== null) {
            $this->zoneRepair->setClusterZone(null);
        }

        // set the owning side of the relation if necessary
        if ($zoneRepair !== null && $zoneRepair->getClusterZone() !== $this) {
            $zoneRepair->setClusterZone($this);
        }

        $this->zoneRepair = $zoneRepair;

        return $this;
    }

    /**
     * @return Collection<int, ZoneInfrastructureSheet>
     */
    public function getZoneInfrastructureSheets(): Collection
    {
        return $this->zoneInfrastructureSheets;
    }

    public function addZoneInfrastructureSheet(ZoneInfrastructureSheet $zoneInfrastructureSheet): self
    {
        if (!$this->zoneInfrastructureSheets->contains($zoneInfrastructureSheet)) {
            $this->zoneInfrastructureSheets[] = $zoneInfrastructureSheet;
            $zoneInfrastructureSheet->setZone($this);
        }

        return $this;
    }

    public function removeZoneInfrastructureSheet(ZoneInfrastructureSheet $zoneInfrastructureSheet): self
    {
        if ($this->zoneInfrastructureSheets->removeElement($zoneInfrastructureSheet)) {
            // set the owning side to null (unless already changed)
            if ($zoneInfrastructureSheet->getZone() === $this) {
                $zoneInfrastructureSheet->setZone(null);
            }
        }

        return $this;
    }

    public function getType(): ?ZoneType
    {
        return $this->type;
    }

    public function setType(?ZoneType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMaxEquipmentDeliveryDeadline()
    {
        if(count($this->zoneInfrastructureSheets) > 0)
        {
            $date =  $this->zoneInfrastructureSheets[0]->getDeliveryDate();

            foreach ($this->zoneInfrastructureSheets as $i)
            {
                $_date = $i->getDeliveryDate();

                if($date < $_date)
                {
                    $date = $_date;
                }
            }

            return $date;
        }
        else
        {
            return null;
        }

    }
    public function getMinEquipmentDeliveryDeadline()
    {
        if(count($this->zoneInfrastructureSheets) > 0)
        {
            $date =  $this->zoneInfrastructureSheets[0]->getDeliveryDate();

            foreach ($this->zoneInfrastructureSheets as $i)
            {
                $_date = $i->getDeliveryDate();

                if($date > $_date and $_date != null)
                {
                    $date = $_date;
                }
            }

            return $date;
        }
        else
        {
            return null;
        }

    }

    public function isDoNotTake(): ?bool
    {
        return $this->doNotTake;
    }

    public function setDoNotTake(?bool $doNotTake): self
    {
        $this->doNotTake = $doNotTake;

        return $this;
    }

    public function getCountOfEquipment()
    {

        if($this->getZoneInfrastructureSheets())
        {
            $arr = [
              'total' => 0,
              'fact' => 0,
              'putInOperation' => 0
            ];
            foreach ($this->getZoneInfrastructureSheets() as $infractSheet)
            {
                $arr['total'] += $infractSheet->getTotalNumber();
                $arr['fact'] += $infractSheet->getFactNumber();
                $arr['putInOperation'] += $infractSheet->getPutIntoOperation();
            }

            return $arr;
        }
        else{
            return null;
        }
    }
    public function getCountOfEquipmentByType()
    {

        if($this->getZoneInfrastructureSheets())
        {
            $arr = [
              'furniture' => 0,
              'PO' => 0,
              'equipment' => 0,
              'furniture_put' => 0,
              'PO_put' => 0,
              'equipment_put' => 0,
              'furniture_fact' => 0,
              'PO_fact' => 0,
              'equipment_fact' => 0,

            ];
            foreach ($this->getZoneInfrastructureSheets() as $infractSheet)
            {
//                dump(mb_strtolower($infractSheet->getType(),'UTF-8') );
                if(mb_strtolower($infractSheet->getType(),'UTF-8') == 'мебель')
                {
                    $arr['furniture'] += $infractSheet->getTotalNumber();
                    $arr['furniture_fact'] += $infractSheet->getFactNumber();
                    $arr['furniture_put'] += $infractSheet->getPutIntoOperation();

                }

                if(str_contains(mb_strtolower($infractSheet->getType(),'UTF-8'),'оборудование'))
                {
                    $arr['equipment'] += $infractSheet->getTotalNumber();
                    $arr['equipment_fact'] += $infractSheet->getFactNumber();
                    $arr['equipment_put'] += $infractSheet->getPutIntoOperation();
                }

                if(mb_strtolower($infractSheet->getType(),'UTF-8') == 'по')
                {
                    $arr['PO'] += $infractSheet->getTotalNumber();
                    $arr['PO_fact'] += $infractSheet->getFactNumber();
                    $arr['PO_put'] += $infractSheet->getPutIntoOperation();
                }

            }
//            dd($arr);
            return $arr;
        }
        else{
            return null;
        }
    }

    public function getAllComments()
    {
        if($this->getZoneInfrastructureSheets())
        {
            $arr = [];
            foreach ($this->getZoneInfrastructureSheets() as $infractSheet)
            {
                if($infractSheet->getComment())
                    array_push($arr, $infractSheet->getComment());
            }

            return $arr;
        }
        else{
            return [];
        }
    }
}
