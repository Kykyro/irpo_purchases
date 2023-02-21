<?php

namespace App\Entity;

use App\Repository\ClusterZoneRepository;
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

    function __construct() {
        $this->setZoneRepair(new ZoneRepair());
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
}
