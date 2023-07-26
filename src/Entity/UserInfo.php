<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $extraFundsOO;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $extraFundsEconomicSector;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $financingFundsOfSubject;

    /**
     * @ORM\OneToOne(targetEntity=ClusterDocument::class, mappedBy="userInfo", cascade={"persist", "remove"})
     */
    private $clusterDocument;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $listOfAnotherOrganization = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $initiatorOfCreation;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $uGPS = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $zone = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $curator;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ogrnip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\ManyToMany(targetEntity=Employers::class, inversedBy="userInfos")
     */
    private $employers;

    /**
     * @ORM\OneToMany(targetEntity=ClusterPerfomanceIndicators::class, mappedBy="userInfo")
     * @ORM\OrderBy({"year" = "ASC"})
     */
    private $clusterPerfomanceIndicators;




    function __construct()
    {
        $this->setAccessToPurchases(false);
//        $this->uGPS = new ArrayCollection();
$this->employers = new ArrayCollection();
$this->clusterPerfomanceIndicators = new ArrayCollection();
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
        $arr = [];
        foreach ($this->employers as $employer)
        {
            array_push($arr, $employer->getName());
        }
        return $arr;

        return $this->listOfEmployers;
    }
    public function getListOfEmployersOld(): ?array
    {


        return $this->listOfEmployers;
    }
    public function setListOfEmployers(?array $listOfEmployers): self
    {
        $this->listOfEmployers = $listOfEmployers;

        return $this;
    }

    public function getExtraFundsOO(): ?float
    {
        return $this->extraFundsOO;
    }

    public function setExtraFundsOO(?float $extraFundsOO): self
    {
        $this->extraFundsOO = $extraFundsOO;

        return $this;
    }

    public function getExtraFundsEconomicSector(): ?float
    {
        return $this->extraFundsEconomicSector;
    }

    public function setExtraFundsEconomicSector(?float $extraFundsEconomicSector): self
    {
        $this->extraFundsEconomicSector = $extraFundsEconomicSector;

        return $this;
    }

    public function getFinancingFundsOfSubject(): ?float
    {
        return $this->financingFundsOfSubject;
    }

    public function setFinancingFundsOfSubject(?float $financingFundsOfSubject): self
    {
        $this->financingFundsOfSubject = $financingFundsOfSubject;

        return $this;
    }

    public function getClusterDocument(): ?ClusterDocument
    {
        return $this->clusterDocument;
    }

    public function setClusterDocument(?ClusterDocument $clusterDocument): self
    {
        // unset the owning side of the relation if necessary
        if ($clusterDocument === null && $this->clusterDocument !== null) {
            $this->clusterDocument->setUserInfo(null);
        }

        // set the owning side of the relation if necessary
        if ($clusterDocument !== null && $clusterDocument->getUserInfo() !== $this) {
            $clusterDocument->setUserInfo($this);
        }

        $this->clusterDocument = $clusterDocument;

        return $this;
    }

    public function getListOfAnotherOrganization(): ?array
    {
        return $this->listOfAnotherOrganization;
    }

    public function setListOfAnotherOrganization(?array $listOfAnotherOrganization): self
    {
        $this->listOfAnotherOrganization = $listOfAnotherOrganization;

        return $this;
    }

    public function getInitiatorOfCreation(): ?string
    {
        return $this->initiatorOfCreation;
    }

    public function setInitiatorOfCreation(?string $initiatorOfCreation): self
    {
        $this->initiatorOfCreation = $initiatorOfCreation;

        return $this;
    }

    public function getUGPS(): ?array
    {
        return $this->uGPS;
    }

    public function setUGPS(?array $UGPS): self
    {
        $this->uGPS = $UGPS;

        return $this;
    }

    public function getZone(): ?array
    {
        return $this->zone;
    }

    public function setZone(?array $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCurator(): ?string
    {
        return $this->curator;
    }

    public function setCurator(?string $curator): self
    {
        $this->curator = $curator;

        return $this;
    }

    public function getOgrnip(): ?string
    {
        return $this->ogrnip;
    }

    public function setOgrnip(?string $ogrnip): self
    {
        $this->ogrnip = $ogrnip;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

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

    /**
     * @return Collection<int, ClusterPerfomanceIndicators>
     */
    public function getClusterPerfomanceIndicators(): Collection
    {
        return $this->clusterPerfomanceIndicators;
    }

    public function addClusterPerfomanceIndicator(ClusterPerfomanceIndicators $clusterPerfomanceIndicator): self
    {
        if (!$this->clusterPerfomanceIndicators->contains($clusterPerfomanceIndicator)) {
            $this->clusterPerfomanceIndicators[] = $clusterPerfomanceIndicator;
            $clusterPerfomanceIndicator->setUserInfo($this);
        }

        return $this;
    }

    public function removeClusterPerfomanceIndicator(ClusterPerfomanceIndicators $clusterPerfomanceIndicator): self
    {
        if ($this->clusterPerfomanceIndicators->removeElement($clusterPerfomanceIndicator)) {
            // set the owning side to null (unless already changed)
            if ($clusterPerfomanceIndicator->getUserInfo() === $this) {
                $clusterPerfomanceIndicator->setUserInfo(null);
            }
        }

        return $this;
    }


}
