<?php

namespace App\Entity;

use App\Repository\CofinancingFundsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CofinancingFundsRepository::class)
 */
class CofinancingFunds
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
    private $regionFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $educationFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $employerFunds;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="cofinancingFunds", cascade={"persist", "remove"})
     */
    private $user;

    function __construct($user)
    {
        $this->user = $user;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegionFunds(): ?string
    {
        return $this->regionFunds;
    }

    public function setRegionFunds(?string $regionFunds): self
    {
        $this->regionFunds = $regionFunds;

        return $this;
    }

    public function getEducationFunds(): ?string
    {
        return $this->educationFunds;
    }

    public function setEducationFunds(?string $educationFunds): self
    {
        $this->educationFunds = $educationFunds;

        return $this;
    }

    public function getEmployerFunds(): ?string
    {
        return $this->employerFunds;
    }

    public function setEmployerFunds(?string $employerFunds): self
    {
        $this->employerFunds = $employerFunds;

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
}
