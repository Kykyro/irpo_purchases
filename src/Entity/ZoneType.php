<?php

namespace App\Entity;

use App\Repository\ZoneTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneTypeRepository::class)
 */
class ZoneType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ClusterZone::class, mappedBy="type")
     */
    private $clusterZones;

    public function __construct()
    {
        $this->clusterZones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ClusterZone>
     */
    public function getClusterZones(): Collection
    {
        return $this->clusterZones;
    }

    public function addClusterZone(ClusterZone $clusterZone): self
    {
        if (!$this->clusterZones->contains($clusterZone)) {
            $this->clusterZones[] = $clusterZone;
            $clusterZone->setType($this);
        }

        return $this;
    }

    public function removeClusterZone(ClusterZone $clusterZone): self
    {
        if ($this->clusterZones->removeElement($clusterZone)) {
            // set the owning side to null (unless already changed)
            if ($clusterZone->getType() === $this) {
                $clusterZone->setType(null);
            }
        }

        return $this;
    }
}
