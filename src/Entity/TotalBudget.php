<?php

namespace App\Entity;

use App\Repository\TotalBudgetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TotalBudgetRepository::class)
 */
class TotalBudget
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $federal;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $employeers;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $subject;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $Edicational;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFederal(): ?string
    {
        return $this->federal;
    }

    public function setFederal(?string $federal): self
    {
        $this->federal = $federal;

        return $this;
    }

    public function getEmployeers(): ?string
    {
        return $this->employeers;
    }

    public function setEmployeers(?string $employeers): self
    {
        $this->employeers = $employeers;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getEdicational(): ?string
    {
        return $this->Edicational;
    }

    public function setEdicational(?string $Edicational): self
    {
        $this->Edicational = $Edicational;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }
}
