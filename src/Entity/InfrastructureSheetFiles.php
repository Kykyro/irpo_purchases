<?php

namespace App\Entity;

use App\Repository\InfrastructureSheetFilesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InfrastructureSheetFilesRepository::class)
 */
class InfrastructureSheetFiles
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Industry::class)
     */
    private $industry;

    /**
     * @ORM\ManyToOne(targetEntity=UGPS::class)
     */
    private $UGPS;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hide;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getfile(): ?string
    {
        return $this->file;
    }

    public function setfile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getIndustry(): ?Industry
    {
        return $this->industry;
    }

    public function setIndustry(?Industry $industry): self
    {
        $this->industry = $industry;

        return $this;
    }

    public function getUGPS(): ?UGPS
    {
        return $this->UGPS;
    }

    public function setUGPS(?UGPS $UGPS): self
    {
        $this->UGPS = $UGPS;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isHide(): ?bool
    {
        return $this->hide;
    }

    public function setHide(?bool $hide): self
    {
        $this->hide = $hide;

        return $this;
    }
}
