<?php

namespace App\Entity;

use App\Repository\RepairDumpGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RepairDumpGroupRepository::class)
 */
class RepairDumpGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=RepairDump::class, mappedBy="repairDumpGroup")
     */
    private $repairDump;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->repairDump = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, RepairDump>
     */
    public function getRepairDump(): Collection
    {
        return $this->repairDump;
    }

    public function addRepairDump(RepairDump $repairDump): self
    {
        if (!$this->repairDump->contains($repairDump)) {
            $this->repairDump[] = $repairDump;
            $repairDump->setRepairDumpGroup($this);
        }

        return $this;
    }

    public function removeRepairDump(RepairDump $repairDump): self
    {
        if ($this->repairDump->removeElement($repairDump)) {
            // set the owning side to null (unless already changed)
            if ($repairDump->getRepairDumpGroup() === $this) {
                $repairDump->setRepairDumpGroup(null);
            }
        }

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
}
