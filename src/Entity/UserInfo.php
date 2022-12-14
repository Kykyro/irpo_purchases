<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserInfoRepository::class)
 */
class UserInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=RfSubject::class)
     */
    private $rf_subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $organization;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $educational_organization;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cluster;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Declared_industry;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRfSubject(): ?RfSubject
    {
        return $this->rf_subject;
    }

    public function setRfSubject(?RfSubject $rf_subject): self
    {
        $this->rf_subject = $rf_subject;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(?string $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getEducationalOrganization(): ?string
    {
        return $this->educational_organization;
    }

    public function setEducationalOrganization(?string $educational_organization): self
    {
        $this->educational_organization = $educational_organization;

        return $this;
    }

    public function getCluster(): ?string
    {
        return $this->cluster;
    }

    public function setCluster(?string $cluster): self
    {
        $this->cluster = $cluster;

        return $this;
    }

    public function getDeclaredIndustry(): ?string
    {
        return $this->Declared_industry;
    }

    public function setDeclaredIndustry(?string $Declared_industry): self
    {
        $this->Declared_industry = $Declared_industry;

        return $this;
    }

    public function isAllFull() : bool
    {

        if(is_null($this->getRfSubject()))
            return false;

        if(is_null($this->organization))
            return false;
        if(is_null($this->educational_organization))
            return false;
        if(is_null($this->cluster))
            return false;
        if(is_null($this->Declared_industry))
            return false;

        return true;
    }
    public function isNOTAllFull() : bool
    {

        if(is_null($this->getRfSubject()))
            return true;

        if(is_null($this->organization))
            return true;
        if(is_null($this->educational_organization))
            return true;
        if(is_null($this->cluster))
            return true;
        if(is_null($this->Declared_industry))
            return true;

        return false;
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
