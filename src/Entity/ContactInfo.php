<?php

namespace App\Entity;

use App\Repository\ContactInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactInfoRepository::class)
 */
class ContactInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=UserInfo::class, inversedBy="contactInfo", cascade={"persist", "remove"})
     */
    private $userInfo;

    /**
     * @ORM\OneToOne(targetEntity=CluterDirector::class, inversedBy="contactInfo", cascade={"persist", "remove"})
     */
    private $director;

    /**
     * @ORM\OneToMany(targetEntity=ResponsibleContact::class, mappedBy="contactInfo", cascade={"persist"})
     */
    private $responsibleContacts;

    /**
     * @ORM\OneToMany(targetEntity=EmployersContact::class, mappedBy="contactInfo",cascade={"persist"})
     */
    private $employersContacts;

    function __construct($userInfo)
    {
      $this->userInfo =   $userInfo;
      $this->director = new CluterDirector();
      $this->responsibleContacts = new ArrayCollection();
      $this->employersContacts = new ArrayCollection();
      foreach ($this->userInfo->getEmployers() as $employer)
      {
          $empCont = new EmployersContact();
          $empCont->setEmployer($employer);
          $this->addEmployersContact($empCont);
      }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserInfo(): ?UserInfo
    {
        return $this->userInfo;
    }

    public function setUserInfo(?UserInfo $userInfo): self
    {
        $this->userInfo = $userInfo;

        return $this;
    }

    public function getDirector(): ?CluterDirector
    {
        return $this->director;
    }

    public function setDirector(?CluterDirector $director): self
    {
        $this->director = $director;

        return $this;
    }

    /**
     * @return Collection<int, ResponsibleContact>
     */
    public function getResponsibleContacts(): Collection
    {
        return $this->responsibleContacts;
    }

    public function addResponsibleContact(ResponsibleContact $responsibleContact): self
    {
        if (!$this->responsibleContacts->contains($responsibleContact)) {
            $this->responsibleContacts[] = $responsibleContact;
            $responsibleContact->setContactInfo($this);
        }

        return $this;
    }

    public function removeResponsibleContact(ResponsibleContact $responsibleContact): self
    {
        if ($this->responsibleContacts->removeElement($responsibleContact)) {
            // set the owning side to null (unless already changed)
            if ($responsibleContact->getContactInfo() === $this) {
                $responsibleContact->setContactInfo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EmployersContact>
     */
    public function getEmployersContacts(): Collection
    {
        return $this->employersContacts;
    }

    public function addEmployersContact(EmployersContact $employersContact): self
    {
        if (!$this->employersContacts->contains($employersContact)) {
            $this->employersContacts[] = $employersContact;
            $employersContact->setContactInfo($this);
        }

        return $this;
    }

    public function removeEmployersContact(EmployersContact $employersContact): self
    {
        if ($this->employersContacts->removeElement($employersContact)) {
            // set the owning side to null (unless already changed)
            if ($employersContact->getContactInfo() === $this) {
                $employersContact->setContactInfo(null);
            }
        }

        return $this;
    }
}
