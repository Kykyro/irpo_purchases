<?php

namespace App\Entity;

use App\Repository\CofinancingCommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CofinancingCommentRepository::class)
 */
class CofinancingComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="cofinancingComment", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $regionFunds;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $educationFunds;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $employerFunds;

    function __construct($user)
    {
        $this->user = $user;

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
}
