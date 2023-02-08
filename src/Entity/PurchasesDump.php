<?php

namespace App\Entity;

use App\Repository\PurchasesDumpRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchasesDumpRepository::class)
 */
class PurchasesDump
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchasesDumps")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity=PurchasesDumpData::class, cascade={"persist", "remove"})
     */
    private $dump;


    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable('now'));
        $this->setDump(new PurchasesDumpData());
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDump(): ?PurchasesDumpData
    {
        return $this->dump;
    }

    public function setDump(?PurchasesDumpData $dump): self
    {
        $this->dump = $dump;

        return $this;
    }


}
