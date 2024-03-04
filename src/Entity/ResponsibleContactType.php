<?php

namespace App\Entity;

use App\Repository\ResponsibleContactTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResponsibleContactTypeRepository::class)
 */
class ResponsibleContactType
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
     * @ORM\ManyToMany(targetEntity=ResponsibleContact::class, inversedBy="responsibleContactTypes",cascade={"persist"})
     */
    private $responsibleContact;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sort;

    public function __construct()
    {
        $this->responsibleContact = new ArrayCollection();
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
     * @return Collection<int, ResponsibleContact>
     */
    public function getResponsibleContact(): Collection
    {
        return $this->responsibleContact;
    }

    public function addResponsibleContact(ResponsibleContact $responsibleContact): self
    {
        if (!$this->responsibleContact->contains($responsibleContact)) {
            $this->responsibleContact[] = $responsibleContact;
        }

        return $this;
    }

    public function removeResponsibleContact(ResponsibleContact $responsibleContact): self
    {
        $this->responsibleContact->removeElement($responsibleContact);

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
