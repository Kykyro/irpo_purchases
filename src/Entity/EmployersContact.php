<?php

namespace App\Entity;

use App\Repository\EmployersContactRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployersContactRepository::class)
 */
class EmployersContact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employers::class, inversedBy="employersContacts")
     */
    private $employer;

    /**
     * @ORM\ManyToOne(targetEntity=ContactInfo::class, inversedBy="employersContacts")
     */
    private $contactInfo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $FIO;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $post;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployer(): ?Employers
    {
        return $this->employer;
    }

    public function setEmployer(?Employers $employer): self
    {
        $this->employer = $employer;

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

    public function getFIO(): ?string
    {
        return $this->FIO;
    }

    public function setFIO(?string $FIO): self
    {
        $this->FIO = $FIO;

        return $this;
    }

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(?string $post): self
    {
        $this->�post = $post;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
