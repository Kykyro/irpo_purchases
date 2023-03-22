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
}
