<?php

namespace App\Entity;

use App\Repository\FileCheckRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileCheckRepository::class)
 */
class FileCheck
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $filename;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $checked;

    /**
     * @ORM\ManyToOne(targetEntity=ProcurementProcedures::class, inversedBy="fileChecks")
     */
    private $purchases;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function isChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(?bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }

    public function getPurchases(): ?ProcurementProcedures
    {
        return $this->purchases;
    }

    public function setPurchases(?ProcurementProcedures $purchases): self
    {
        $this->purchases = $purchases;

        return $this;
    }
}
