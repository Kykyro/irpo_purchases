<?php

namespace App\Entity;

use App\Repository\CofinancingScenarioFileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CofinancingScenarioFileRepository::class)
 */
class CofinancingScenarioFile
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
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=CofinancingScenario::class, inversedBy="files")
     */
    private $cofinancingScenario;

    function __construct()
    {
        $this->status = 'На проверке';
        $this->createdAt = new \DateTimeImmutable('now');
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

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

    public function getCofinancingScenario(): ?CofinancingScenario
    {
        return $this->cofinancingScenario;
    }

    public function setCofinancingScenario(?CofinancingScenario $cofinancingScenario): self
    {
        $this->cofinancingScenario = $cofinancingScenario;

        return $this;
    }
}
