<?php

namespace App\Entity;

use App\Repository\ClusterAddressesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClusterAddressesRepository::class)
 */
class ClusterAddresses
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
    private $addresses;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="clusterAddresses")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ClusterZone::class, mappedBy="addres")
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

    public function getAddresses(): ?string
    {
        return $this->addresses;
    }

    public function setAddresses(?string $addresses): self
    {
        $this->addresses = $addresses;

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
            $clusterZone->setAddres($this);
        }

        return $this;
    }

    public function removeClusterZone(ClusterZone $clusterZone): self
    {
        if ($this->clusterZones->removeElement($clusterZone)) {
            // set the owning side to null (unless already changed)
            if ($clusterZone->getAddres() === $this) {
                $clusterZone->setAddres(null);
            }
        }

        return $this;
    }
}
