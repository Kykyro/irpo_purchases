<?php

namespace App\Entity;

use App\Repository\ClusterDocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClusterDocumentRepository::class)
 */
class ClusterDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=UserInfo::class, inversedBy="clusterDocument", cascade={"persist", "remove"})
     */
    private $userInfo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $partnershipAgreement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $financialAgreement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $infrastructureSheet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $designProject;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activityProgram;

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

    public function getPartnershipAgreement(): ?string
    {
        return $this->partnershipAgreement;
    }

    public function setPartnershipAgreement(?string $partnershipAgreement): self
    {
        $this->partnershipAgreement = $partnershipAgreement;

        return $this;
    }

    public function getFinancialAgreement(): ?string
    {
        return $this->financialAgreement;
    }

    public function setFinancialAgreement(?string $financialAgreement): self
    {
        $this->financialAgreement = $financialAgreement;

        return $this;
    }

    public function getInfrastructureSheet(): ?string
    {
        return $this->infrastructureSheet;
    }

    public function setInfrastructureSheet(?string $infrastructureSheet): self
    {
        $this->infrastructureSheet = $infrastructureSheet;

        return $this;
    }

    public function getDesignProject(): ?string
    {
        return $this->designProject;
    }

    public function setDesignProject(?string $designProject): self
    {
        $this->designProject = $designProject;

        return $this;
    }

    public function getActivityProgram(): ?string
    {
        return $this->activityProgram;
    }

    public function setActivityProgram(?string $activityProgram): self
    {
        $this->activityProgram = $activityProgram;

        return $this;
    }
}
