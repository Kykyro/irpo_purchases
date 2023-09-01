<?php

namespace App\Entity;

use App\Repository\ContractCertificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContractCertificationRepository::class)
 */
class ContractCertification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=UserInfo::class, inversedBy="contractCertifications")
     */
    private $userInfo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $purchasesTable;

    function __construct(?UserInfo $userInfo)
    {
        $this->setStatus("Справка не прислана");
        $this->setUserInfo($userInfo);
        $this->setCreatedAt(new \DateTimeImmutable('now'));
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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPurchasesTable(): ?string
    {
        return $this->purchasesTable;
    }

    public function setPurchasesTable(?string $purchasesTable): self
    {
        $this->purchasesTable = $purchasesTable;

        return $this;
    }
}
