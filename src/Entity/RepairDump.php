<?php

namespace App\Entity;

use App\Repository\RepairDumpRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @ORM\Entity(repositoryClass=RepairDumpRepository::class)
 */
class RepairDump
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $dump;

    /**
     * @ORM\ManyToOne(targetEntity=ZoneRepair::class, inversedBy="repairDumps")
     */
    private $repair;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="repairDumps")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=RepairDumpGroup::class, inversedBy="repairDump")
     */
    private $repairDumpGroup;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDump(): ?string
    {
        return $this->dump;
    }

    public function setDump(?string $dump): self
    {
        $this->dump = $dump;

        return $this;
    }

    public function getRepair(): ?ZoneRepair
    {
        return $this->repair;
    }

    public function setRepair(?ZoneRepair $repair): self
    {
        $this->repair = $repair;

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

    public function getRepairDumpGroup(): ?RepairDumpGroup
    {
        return $this->repairDumpGroup;
    }

    public function setRepairDumpGroup(?RepairDumpGroup $repairDumpGroup): self
    {
        $this->repairDumpGroup = $repairDumpGroup;

        return $this;
    }
    public function getEntity(SerializerInterface $serializer)
    {
        return $serializer->deserialize($this->getDump(), 'App\Entity\ZoneRepair' , 'json');
    }

}
