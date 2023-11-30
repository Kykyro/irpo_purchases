<?php

namespace App\Entity;

use App\Repository\ProfEduOrgRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfEduOrgRepository::class)
 */
class ProfEduOrg
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=RfSubject::class, inversedBy="profEduOrgs")
     */
    private $region;

    /**
     *
     * @Groups("search")
     * @ORM\Column(type="string", length=500, nullable=true)
     *
     */
    private $fullName;

    /**
     *
     * @ORM\Column(type="string", length=500, nullable=true)
     *
     */
    private $shortName;

    /**
     * @ORM\OneToMany(targetEntity=Building::class, mappedBy="organization")
     */
    private $buildings;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    public function __construct()
    {
        $this->buildings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegion(): ?RfSubject
    {
        return $this->region;
    }

    public function setRegion(?RfSubject $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * @return Collection<int, Building>
     */
    public function getBuildings(): Collection
    {
        return $this->buildings;
    }

    public function addBuilding(Building $building): self
    {
        if (!$this->buildings->contains($building)) {
            $this->buildings[] = $building;
            $building->setOrganization($this);
        }

        return $this;
    }

    public function removeBuilding(Building $building): self
    {
        if ($this->buildings->removeElement($building)) {
            // set the owning side to null (unless already changed)
            if ($building->getOrganization() === $this) {
                $building->setOrganization(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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
