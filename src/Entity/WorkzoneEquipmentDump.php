<?php

namespace App\Entity;

use App\Repository\WorkzoneEquipmentDumpRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkzoneEquipmentDumpRepository::class)
 */
class WorkzoneEquipmentDump
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $changes = [];

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=WorkzoneEquipment::class, inversedBy="workzoneEquipmentDumps")
     */
    private $equipment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChanges(): ?array
    {
        return $this->changes;
    }

    public function setChanges(?array $changes): self
    {
        $this->changes = $changes;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEquipment(): ?WorkzoneEquipment
    {
        return $this->equipment;
    }

    public function setEquipment(?WorkzoneEquipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }
}
