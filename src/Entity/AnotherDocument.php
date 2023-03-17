<?php

namespace App\Entity;

use App\Repository\AnotherDocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnotherDocumentRepository::class)
 */
class AnotherDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $version;

    /**
     * @ORM\ManyToOne(targetEntity=ProcurementProcedures::class, inversedBy="anotherDocuments")
     */
    private $purchases;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): self
    {
        $this->version = $version;

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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
    public function getDateFormat()
    {
        if(is_null($this->date))
            return '';
        return $this->date->format('d.m.Y');
    }

}
