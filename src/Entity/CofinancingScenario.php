<?php

namespace App\Entity;

use App\Repository\CofinancingScenarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CofinancingScenarioRepository::class)
 */
class CofinancingScenario
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $scenario;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cofinancingScenarios")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=CofinancingScenarioFile::class, mappedBy="cofinancingScenario")
     */
    private $files;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $employersFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $regionFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $educationFunds;

    function __construct($user)
    {
        $this->user = $user;
        $this->createdAt = new \DateTimeImmutable('now');
        $this->files = new ArrayCollection();
        $this->status = 'На проверке';
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getScenario(): ?string
    {
        return $this->scenario;
    }

    public function setScenario(?string $scenario): self
    {
        $this->scenario = $scenario;

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
     * @return Collection<int, CofinancingScenarioFile>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(CofinancingScenarioFile $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setCofinancingScenario($this);
        }

        return $this;
    }

    public function removeFile(CofinancingScenarioFile $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getCofinancingScenario() === $this) {
                $file->setCofinancingScenario(null);
            }
        }

        return $this;
    }

    public function getEmployersFunds(): ?string
    {
        return $this->employersFunds;
    }

    public function setEmployersFunds(?string $employersFunds): self
    {
        $this->employersFunds = $employersFunds;

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
}
