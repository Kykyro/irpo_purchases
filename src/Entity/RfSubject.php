<?php

namespace App\Entity;

use App\Repository\RfSubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RfSubjectRepository::class)
 */
class RfSubject
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $timezone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $district;

    /**
     * @ORM\OneToMany(targetEntity=ProfEduOrg::class, mappedBy="region")
     */
    private $profEduOrgs;



    public function __construct()
    {
        $this->fullName = new ArrayCollection();
        $this->profEduOrgs = new ArrayCollection();
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

    public function getTimezone(): ?int
    {
        return $this->timezone;
    }

    public function setTimezone(?int $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(?string $district): self
    {
        $this->district = $district;

        return $this;
    }

    /**
     * @return Collection<int, ProfEduOrg>
     */
    public function getProfEduOrgs(): Collection
    {
        return $this->profEduOrgs;
    }

    public function addProfEduOrg(ProfEduOrg $profEduOrg): self
    {
        if (!$this->profEduOrgs->contains($profEduOrg)) {
            $this->profEduOrgs[] = $profEduOrg;
            $profEduOrg->setRegion($this);
        }

        return $this;
    }

    public function removeProfEduOrg(ProfEduOrg $profEduOrg): self
    {
        if ($this->profEduOrgs->removeElement($profEduOrg)) {
            // set the owning side to null (unless already changed)
            if ($profEduOrg->getRegion() === $this) {
                $profEduOrg->setRegion(null);
            }
        }

        return $this;
    }

}
