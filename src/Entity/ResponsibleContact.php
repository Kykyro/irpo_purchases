<?php

namespace App\Entity;

use App\Repository\ResponsibleContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResponsibleContactRepository::class)
 */
class ResponsibleContact
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
    private $FIO;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\ManyToOne(targetEntity=ContactInfo::class, inversedBy="responsibleContacts", cascade={"persist"})
     */
    private $contactInfo;

    /**
     * @ORM\ManyToMany(targetEntity=ResponsibleContactType::class, mappedBy="responsibleContact",cascade={"persist"})
     */
    private $responsibleContactTypes;

    public function __construct()
    {
        $this->responsibleContactTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFIO(): ?string
    {
        return $this->FIO;
    }

    public function setFIO(?string $FIO): self
    {
        $this->FIO = $FIO;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getContactInfo(): ?ContactInfo
    {
        return $this->contactInfo;
    }

    public function setContactInfo(?ContactInfo $contactInfo): self
    {
        $this->contactInfo = $contactInfo;

        return $this;
    }

    /**
     * @return Collection<int, ResponsibleContactType>
     */
    public function getResponsibleContactTypes(): Collection
    {
        return $this->responsibleContactTypes;
    }

    public function addResponsibleContactType(ResponsibleContactType $responsibleContactType): self
    {
        if (!$this->responsibleContactTypes->contains($responsibleContactType)) {
            $this->responsibleContactTypes[] = $responsibleContactType;
            $responsibleContactType->addResponsibleContact($this);
        }

        return $this;
    }

    public function removeResponsibleContactType(ResponsibleContactType $responsibleContactType): self
    {
        if ($this->responsibleContactTypes->removeElement($responsibleContactType)) {
            $responsibleContactType->removeResponsibleContact($this);
        }

        return $this;
    }
}
