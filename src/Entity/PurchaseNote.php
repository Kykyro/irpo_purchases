<?php

namespace App\Entity;

use App\Repository\PurchaseNoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseNoteRepository::class)
 */
class PurchaseNote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProcurementProcedures::class, inversedBy="purchaseNotes")
     */
    private $purchase;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchaseNotes")
     */
    private $curator;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRead;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $creadtedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchase(): ?ProcurementProcedures
    {
        return $this->purchase;
    }

    public function setPurchase(?ProcurementProcedures $purchase): self
    {
        $this->purchase = $purchase;

        return $this;
    }

    public function getCurator(): ?User
    {
        return $this->curator;
    }

    public function setCurator(?User $curator): self
    {
        $this->curator = $curator;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(?bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getCreadtedAt(): ?\DateTimeImmutable
    {
        return $this->creadtedAt;
    }

    public function setCreadtedAt(?\DateTimeImmutable $creadtedAt): self
    {
        $this->creadtedAt = $creadtedAt;

        return $this;
    }
}
