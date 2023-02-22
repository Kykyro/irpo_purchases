<?php

namespace App\Entity;

use App\Repository\ZoneInfrastructureSheetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneInfrastructureSheetRepository::class)
 */
class ZoneInfrastructureSheet
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
     * @ORM\Column(type="integer")
     */
    private $totalNumber;

    /**
     * @ORM\ManyToOne(targetEntity=ClusterZone::class, inversedBy="zoneInfrastructureSheets")
     */
    private $zone;

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

    public function getTotalNumber(): ?int
    {
        return $this->totalNumber;
    }

    public function setTotalNumber(int $totalNumber): self
    {
        $this->totalNumber = $totalNumber;

        return $this;
    }

    public function getZone(): ?ClusterZone
    {
        return $this->zone;
    }

    public function setZone(?ClusterZone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }
}
