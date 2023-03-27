<?php

namespace App\Entity;

use App\Repository\PhotosVersionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotosVersionRepository::class)
 */
class PhotosVersion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=ZoneRepair::class, inversedBy="photosVersions")
     */
    private $repair;

    /**
     * @ORM\OneToMany(targetEntity=RepairPhotos::class, mappedBy="version")
     */
    private $repairPhotos;

    public function __construct()
    {
        $this->repairPhotos = new ArrayCollection();
        $this->setCreatedAt(new \DateTimeImmutable('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    /**
     * @return Collection<int, RepairPhotos>
     */
    public function getRepairPhotos(): Collection
    {
        return $this->repairPhotos;
    }

    public function addRepairPhoto(RepairPhotos $repairPhoto): self
    {
        if (!$this->repairPhotos->contains($repairPhoto)) {
            $this->repairPhotos[] = $repairPhoto;
            $repairPhoto->setVersion($this);
        }

        return $this;
    }

    public function removeRepairPhoto(RepairPhotos $repairPhoto): self
    {
        if ($this->repairPhotos->removeElement($repairPhoto)) {
            // set the owning side to null (unless already changed)
            if ($repairPhoto->getVersion() === $this) {
                $repairPhoto->setVersion(null);
            }
        }

        return $this;
    }
}
