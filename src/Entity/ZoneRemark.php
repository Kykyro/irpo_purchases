<?php

namespace App\Entity;

use App\Repository\ZoneRemarkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneRemarkRepository::class)
 */
class ZoneRemark
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=ClusterZone::class, inversedBy="zoneRemarks")
     */
    private $zone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
