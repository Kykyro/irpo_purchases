<?php

namespace App\Entity;

use App\Repository\FavoritesClustersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavoritesClustersRepository::class)
 */
class FavoritesClusters
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="clustersId")
     */
    private $inspectorId;

    /**
     * @ORM\OneToOne(targetEntity=user::class)
     */
    private $clusterId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $cluster;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInspectorId(): ?user
    {
        return $this->inspectorId;
    }

    public function setInspectorId(?user $inspectorId): self
    {
        $this->inspectorId = $inspectorId;

        return $this;
    }

    public function getClusterId(): ?user
    {
        return $this->clusterId;
    }

    public function setClusterId(?user $clusterId): self
    {
        $this->clusterId = $clusterId;

        return $this;
    }

    public function getCluster(): ?User
    {
        return $this->cluster;
    }

    public function setCluster(?User $cluster): self
    {
        $this->cluster = $cluster;

        return $this;
    }
}
