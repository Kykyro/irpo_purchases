<?php

namespace App\Entity;

use App\Repository\CertificateFundsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CertificateFundsRepository::class)
 */
class CertificateFunds
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=UserInfo::class, inversedBy="certificateFunds", cascade={"persist", "remove"})
     */
    private $userInfo;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $economicFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $subjectFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $extraFunds;


    function __construct($user_info)
    {
//        $this->setUserInfo($user_info);
        $this->setEconomicFunds($user_info->getExtraFundsEconomicSector()*1000);
        $this->setSubjectFunds($user_info->getFinancingFundsOfSubject()*1000);
        $this->setExtraFunds($user_info->getExtraFundsOO()*1000);
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

    public function getEconomicFunds(): ?string
    {
        return $this->economicFunds;
    }

    public function setEconomicFunds(?string $economicFunds): self
    {
        $this->economicFunds = $economicFunds;

        return $this;
    }

    public function getSubjectFunds(): ?string
    {
        return $this->subjectFunds;
    }

    public function setSubjectFunds(?string $subjectFunds): self
    {
        $this->subjectFunds = $subjectFunds;

        return $this;
    }

    public function getExtraFunds(): ?string
    {
        return $this->extraFunds;
    }

    public function setExtraFunds(?string $extraFunds): self
    {
        $this->extraFunds = $extraFunds;

        return $this;
    }
}
