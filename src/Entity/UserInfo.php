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

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $accessToPurchases;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $studentsCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $programCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $teacherCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $workerCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $studentsCountWithMentor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $jobSecurityCount;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $amountOfFunding;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $amountOfExtraFunds;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $listOfEdicationOrganization = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $listOfEmployers = [];


    function __construct()
    {
        $this->setAccessToPurchases(false);
    }

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

    public function isAccessToPurchases(): ?bool
    {
        return $this->accessToPurchases;
    }

    public function setAccessToPurchases(?bool $accessToPurchases): self
    {
        $this->accessToPurchases = $accessToPurchases;

        return $this;
    }

    public function getStudentsCount(): ?int
    {
        return $this->studentsCount;
    }

    public function setStudentsCount(?int $studentsCount): self
    {
        $this->studentsCount = $studentsCount;

        return $this;
    }

    public function getProgramCount(): ?int
    {
        return $this->programCount;
    }

    public function setProgramCount(?int $programCount): self
    {
        $this->programCount = $programCount;

        return $this;
    }

    public function getTeacherCount(): ?int
    {
        return $this->teacherCount;
    }

    public function setTeacherCount(?int $teacherCount): self
    {
        $this->teacherCount = $teacherCount;

        return $this;
    }

    public function getWorkerCount(): ?int
    {
        return $this->workerCount;
    }

    public function setWorkerCount(?int $workerCount): self
    {
        $this->workerCount = $workerCount;

        return $this;
    }

    public function getStudentsCountWithMentor(): ?int
    {
        return $this->studentsCountWithMentor;
    }

    public function setStudentsCountWithMentor(?int $studentsCountWithMentor): self
    {
        $this->studentsCountWithMentor = $studentsCountWithMentor;

        return $this;
    }

    public function getJobSecurityCount(): ?int
    {
        return $this->jobSecurityCount;
    }

    public function setJobSecurityCount(?int $jobSecurityCount): self
    {
        $this->jobSecurityCount = $jobSecurityCount;

        return $this;
    }

    public function getAmountOfFunding(): ?string
    {
        return $this->amountOfFunding;
    }

    public function setAmountOfFunding(?string $amountOfFunding): self
    {
        $this->amountOfFunding = $amountOfFunding;

        return $this;
    }

    public function getAmountOfExtraFunds(): ?string
    {
        return $this->amountOfExtraFunds;
    }

    public function setAmountOfExtraFunds(?string $amountOfExtraFunds): self
    {
        $this->amountOfExtraFunds = $amountOfExtraFunds;

        return $this;
    }

    public function getListOfEdicationOrganization(): ?array
    {
        return $this->listOfEdicationOrganization;
    }

    public function setListOfEdicationOrganization(?array $listOfEdicationOrganization): self
    {
        $this->listOfEdicationOrganization = $listOfEdicationOrganization;

        return $this;
    }

    public function removeListOfEdicationOrganization($val)
    {
        if (!$this->listOfEdicationOrganization->contains($val)) {
            return;
        }

        $this->listOfEdicationOrganization->removeElement($val);


    }

    public function getListOfEmployers(): ?array
    {
        return $this->listOfEmployers;
    }

    public function setListOfEmployers(?array $listOfEmployers): self
    {
        $this->listOfEmployers = $listOfEmployers;

        return $this;
    }
}
