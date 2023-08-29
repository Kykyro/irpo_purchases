<?php

namespace App\Entity;

use App\Repository\EmployersCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployersCategoryRepository::class)
 */
class EmployersCategory
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
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Employers::class, inversedBy="employersCategories")
     */
    private $employers;

    public function __construct()
    {
        $this->employers = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Employers>
     */
    public function getEmployers(): Collection
    {
        return $this->employers;
    }

    public function addEmployer(Employers $employer): self
    {
        if (!$this->employers->contains($employer)) {
            $this->employers[] = $employer;
        }

        return $this;
    }

    public function removeEmployer(Employers $employer): self
    {
        $this->employers->removeElement($employer);

        return $this;
    }
}
