<?php

namespace App\Entity;

use App\Repository\ReadinessMapCheckRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReadinessMapCheckRepository::class)
 */
class ReadinessMapCheck
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="readinessMapChecks")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ReadinessMapCheckStatus::class, mappedBy="readinessMapCheck")
     */
    private $status;

    function __construct(?User $userInfo)
    {
        $this->setUser($userInfo);
        $this->setCreatedAt(new \DateTimeImmutable('now'));
        $this->status = new ArrayCollection();
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
     * @return Collection<int, ReadinessMapCheckStatus>
     */
    public function getStatus(): Collection
    {
        return $this->status;
    }

    public function addStatus(ReadinessMapCheckStatus $status): self
    {
        if (!$this->status->contains($status)) {
            $this->status[] = $status;
            $status->setReadinessMapCheck($this);
        }

        return $this;
    }

    public function removeStatus(ReadinessMapCheckStatus $status): self
    {
        if ($this->status->removeElement($status)) {
            // set the owning side to null (unless already changed)
            if ($status->getReadinessMapCheck() === $this) {
                $status->setReadinessMapCheck(null);
            }
        }

        return $this;
    }
}
