<?php

namespace App\Entity;

use App\Repository\EquipmentLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipmentLogRepository::class)
 */
class EquipmentLog
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
    private $log;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=WorkzoneEquipment::class, inversedBy="equipmentLogs")
     */
    private $equipment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLog(): ?string
    {
        return $this->log;
    }

    public function setLog(?string $log): self
    {
        $this->log = $log;

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
