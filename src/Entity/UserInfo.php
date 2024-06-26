<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups("search")
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

    /**
     * @ORM\OneToOne(targetEntity=ContactInfo::class, mappedBy="userInfo", cascade={"persist", "remove"})
     */
    private $contactInfo;

    /**
     * @ORM\OneToMany(targetEntity=ContractCertification::class, mappedBy="userInfo")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $contractCertifications;

    /**
     * @ORM\OneToOne(targetEntity=CertificateFunds::class, mappedBy="userInfo", cascade={"persist", "remove"})
     */
    private $certificateFunds;

    /**
     * @ORM\OneToMany(targetEntity=MonitoringCheckOut::class, mappedBy="userInfo")
     */
    private $monitoringCheckOuts;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $FedFunds;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $EduOrgsCount;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $grandFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $fedFundsGrant;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $EmplFundsGrant;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $regionFundsGrant;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $readinessMapChecksRefresh;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $grandOGRN;



    function __construct()
    {
        $this->setAccessToPurchases(false);
//        $this->uGPS = new ArrayCollection();
        $this->employers = new ArrayCollection();
        $this->clusterPerfomanceIndicators = new ArrayCollection();
        $this->contractCertifications = new ArrayCollection();
        $this->monitoringCheckOuts = new ArrayCollection();
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

    public function getContactInfo(): ?ContactInfo
    {
        return $this->contactInfo;
    }

    public function setContactInfo(?ContactInfo $contactInfo): self
    {
        // unset the owning side of the relation if necessary
        if ($contactInfo === null && $this->contactInfo !== null) {
            $this->contactInfo->setUserInfo(null);
        }

        // set the owning side of the relation if necessary
        if ($contactInfo !== null && $contactInfo->getUserInfo() !== $this) {
            $contactInfo->setUserInfo($this);
        }

        $this->contactInfo = $contactInfo;

        return $this;
    }

    /**
     * @return Collection<int, ContractCertification>
     */
    public function getContractCertifications(): Collection
    {
        return $this->contractCertifications;
    }

    public function addContractCertification(ContractCertification $contractCertification): self
    {
        if (!$this->contractCertifications->contains($contractCertification)) {
            $this->contractCertifications[] = $contractCertification;
            $contractCertification->setUserInfo($this);
        }

        return $this;
    }

    public function removeContractCertification(ContractCertification $contractCertification): self
    {
        if ($this->contractCertifications->removeElement($contractCertification)) {
            // set the owning side to null (unless already changed)
            if ($contractCertification->getUserInfo() === $this) {
                $contractCertification->setUserInfo(null);
            }
        }

        return $this;
    }

    public function getCertificateFunds(): ?CertificateFunds
    {
        return $this->certificateFunds;
    }

    public function setCertificateFunds(?CertificateFunds $certificateFunds): self
    {
        // unset the owning side of the relation if necessary
        if ($certificateFunds === null && $this->certificateFunds !== null) {
            $this->certificateFunds->setUserInfo(null);
        }

        // set the owning side of the relation if necessary
        if ($certificateFunds !== null && $certificateFunds->getUserInfo() !== $this) {
            $certificateFunds->setUserInfo($this);
        }

        $this->certificateFunds = $certificateFunds;

        return $this;
    }

    /**
     * @return Collection<int, MonitoringCheckOut>
     */
    public function getMonitoringCheckOuts(): Collection
    {
        return $this->monitoringCheckOuts;
    }

    public function addMonitoringCheckOut(MonitoringCheckOut $monitoringCheckOut): self
    {
        if (!$this->monitoringCheckOuts->contains($monitoringCheckOut)) {
            $this->monitoringCheckOuts[] = $monitoringCheckOut;
            $monitoringCheckOut->setUserInfo($this);
        }

        return $this;
    }

    public function removeMonitoringCheckOut(MonitoringCheckOut $monitoringCheckOut): self
    {
        if ($this->monitoringCheckOuts->removeElement($monitoringCheckOut)) {
            // set the owning side to null (unless already changed)
            if ($monitoringCheckOut->getUserInfo() === $this) {
                $monitoringCheckOut->setUserInfo(null);
            }
        }

        return $this;
    }

    public function getFedFunds(): ?float
    {
        return $this->FedFunds;
    }

    public function setFedFunds(?float $FedFunds): self
    {
        $this->FedFunds = $FedFunds;

        return $this;
    }

    public function getEduOrgsCount(): ?int
    {
        return $this->EduOrgsCount;
    }

    public function setEduOrgsCount(?int $EduOrgsCount): self
    {
        $this->EduOrgsCount = $EduOrgsCount;

        return $this;
    }

    public function getGrandFunds(): ?string
    {
        return $this->grandFunds;
    }

    public function setGrandFunds(?string $grandFunds): self
    {
        $this->grandFunds = $grandFunds;

        return $this;
    }

    public function getFedFundsGrant(): ?string
    {
        return $this->fedFundsGrant;
    }

    public function setFedFundsGrant(?string $fedFundsGrant): self
    {
        $this->fedFundsGrant = $fedFundsGrant;

        return $this;
    }

    public function getEmplFundsGrant(): ?string
    {
        return $this->EmplFundsGrant;
    }

    public function setEmplFundsGrant(?string $EmplFundsGrant): self
    {
        $this->EmplFundsGrant = $EmplFundsGrant;

        return $this;
    }

    public function getRegionFundsGrant(): ?string
    {
        return $this->regionFundsGrant;
    }

    public function setRegionFundsGrant(?string $regionFundsGrant): self
    {
        $this->regionFundsGrant = $regionFundsGrant;

        return $this;
    }

    public function isReadinessMapChecksRefresh(): ?bool
    {
        return $this->readinessMapChecksRefresh;
    }

    public function setReadinessMapChecksRefresh(?bool $readinessMapChecksRefresh): self
    {
        $this->readinessMapChecksRefresh = $readinessMapChecksRefresh;

        return $this;
    }

    public function getGrandOGRN(): ?string
    {
        return $this->grandOGRN;
    }

    public function setGrandOGRN(?string $grandOGRN): self
    {
        $this->grandOGRN = $grandOGRN;

        return $this;
    }


}
