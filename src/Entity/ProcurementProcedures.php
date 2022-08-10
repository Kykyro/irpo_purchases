<?php

namespace App\Entity;

use App\Repository\ProcurementProceduresRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProcurementProceduresRepository::class)
 */
class ProcurementProcedures
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $PurchaseObject;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $MethodOfDetermining;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $PurchaseLink;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $PurchaseNumber;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $DateOfConclusion;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $DeliveryTime;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileDir;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=3, nullable=true)
     */
    private $initialFederalFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=3, nullable=true)
     */
    private $initialFundsOfSubject;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=3, nullable=true)
     */
    private $initialEmployersFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=3, nullable=true)
     */
    private $initialEducationalOrgFunds;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $supplierName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $supplierINN;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $supplierKPP;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=3, nullable=true)
     */
    private $finFederalFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=3, nullable=true)
     */
    private $finFundsOfSubject;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=3, nullable=true)
     */
    private $finEmployersFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=3, nullable=true)
     */
    private $finFundsOfEducationalOrg;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $deadlineDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateOfSummingUp;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $postponementDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $postonementComment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseObject(): ?string
    {
        return $this->PurchaseObject;
    }

    public function setPurchaseObject(?string $PurchaseObject): self
    {
        $this->PurchaseObject = $PurchaseObject;

        return $this;
    }

    public function getMethodOfDetermining(): ?string
    {
        return $this->MethodOfDetermining;
    }

    public function setMethodOfDetermining(?string $MethodOfDetermining): self
    {
        $this->MethodOfDetermining = $MethodOfDetermining;

        return $this;
    }

    public function getPurchaseLink(): ?string
    {
        return $this->PurchaseLink;
    }

    public function setPurchaseLink(?string $PurchaseLink): self
    {
        $this->PurchaseLink = $PurchaseLink;

        return $this;
    }

    public function getPurchaseNumber(): ?int
    {
        return $this->PurchaseNumber;
    }

    public function setPurchaseNumber(?int $PurchaseNumber): self
    {
        $this->PurchaseNumber = $PurchaseNumber;

        return $this;
    }

    public function getDateOfConclusion(): ?\DateTimeInterface
    {
        return $this->DateOfConclusion;
    }

    public function setDateOfConclusion(?\DateTimeInterface $DateOfConclusion): self
    {
        $this->DateOfConclusion = $DateOfConclusion;

        return $this;
    }

    public function getDeliveryTime(): ?\DateTimeInterface
    {
        return $this->DeliveryTime;
    }

    public function setDeliveryTime(?\DateTimeInterface $DeliveryTime): self
    {
        $this->DeliveryTime = $DeliveryTime;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->Comments;
    }

    public function setComments(?string $Comments): self
    {
        $this->Comments = $Comments;

        return $this;
    }

    public function getFileDir(): ?string
    {
        return $this->fileDir;
    }

    public function setFileDir(?string $fileDir): self
    {
        $this->fileDir = $fileDir;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getInitialFederalFunds(): ?string
    {
        return $this->initialFederalFunds;
    }

    public function setInitialFederalFunds(?string $initialFederalFunds): self
    {
        $this->initialFederalFunds = $initialFederalFunds;

        return $this;
    }

    public function getInitialFundsOfSubject(): ?string
    {
        return $this->initialFundsOfSubject;
    }

    public function setInitialFundsOfSubject(?string $initialFundsOfSubject): self
    {
        $this->initialFundsOfSubject = $initialFundsOfSubject;

        return $this;
    }

    public function getInitialEmployersFunds(): ?string
    {
        return $this->initialEmployersFunds;
    }

    public function setInitialEmployersFunds(?string $initialEmployersFunds): self
    {
        $this->initialEmployersFunds = $initialEmployersFunds;

        return $this;
    }

    public function getInitialEducationalOrgFunds(): ?string
    {
        return $this->initialEducationalOrgFunds;
    }

    public function setInitialEducationalOrgFunds(?string $initialEducationalOrgFunds): self
    {
        $this->initialEducationalOrgFunds = $initialEducationalOrgFunds;

        return $this;
    }

    public function getSupplierName(): ?string
    {
        return $this->supplierName;
    }

    public function setSupplierName(?string $supplierName): self
    {
        $this->supplierName = $supplierName;

        return $this;
    }

    public function getSupplierINN(): ?string
    {
        return $this->supplierINN;
    }

    public function setSupplierINN(?string $supplierINN): self
    {
        $this->supplierINN = $supplierINN;

        return $this;
    }

    public function getSupplierKPP(): ?string
    {
        return $this->supplierKPP;
    }

    public function setSupplierKPP(?string $supplierKPP): self
    {
        $this->supplierKPP = $supplierKPP;

        return $this;
    }

    public function getFinFederalFunds(): ?string
    {
        return $this->finFederalFunds;
    }

    public function setFinFederalFunds(?string $finFederalFunds): self
    {
        $this->finFederalFunds = $finFederalFunds;

        return $this;
    }

    public function getFinFundsOfSubject(): ?string
    {
        return $this->finFundsOfSubject;
    }

    public function setFinFundsOfSubject(?string $finFundsOfSubject): self
    {
        $this->finFundsOfSubject = $finFundsOfSubject;

        return $this;
    }

    public function getFinEmployersFunds(): ?string
    {
        return $this->finEmployersFunds;
    }

    public function setFinEmployersFunds(?string $finEmployersFunds): self
    {
        $this->finEmployersFunds = $finEmployersFunds;

        return $this;
    }

    public function getFinFundsOfEducationalOrg(): ?string
    {
        return $this->finFundsOfEducationalOrg;
    }

    public function setFinFundsOfEducationalOrg(?string $finFundsOfEducationalOrg): self
    {
        $this->finFundsOfEducationalOrg = $finFundsOfEducationalOrg;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getDeadlineDate(): ?\DateTimeInterface
    {
        return $this->deadlineDate;
    }

    public function setDeadlineDate(?\DateTimeInterface $deadlineDate): self
    {
        $this->deadlineDate = $deadlineDate;

        return $this;
    }

    public function getDateOfSummingUp(): ?\DateTimeInterface
    {
        return $this->dateOfSummingUp;
    }

    public function setDateOfSummingUp(?\DateTimeInterface $dateOfSummingUp): self
    {
        $this->dateOfSummingUp = $dateOfSummingUp;

        return $this;
    }

    public function getPostponementDate(): ?\DateTimeInterface
    {
        return $this->postponementDate;
    }

    public function setPostponementDate(?\DateTimeInterface $postponementDate): self
    {
        $this->postponementDate = $postponementDate;

        return $this;
    }

    public function getPostonementComment(): ?string
    {
        return $this->postonementComment;
    }

    public function setPostonementComment(?string $postonementComment): self
    {
        $this->postonementComment = $postonementComment;

        return $this;
    }

    public function getAsRow(): array
    {
        $row = [];
        array_push($row,
        $this->getPurchaseObject(),
        $this->getMethodOfDetermining(),
        null,
        $this->getInitialFederalFunds(),
        $this->getInitialFundsOfSubject(),
        $this->getInitialEmployersFunds(),
        $this->getInitialFederalFunds(),
        (is_null($this->getPublicationDate())) ? '' : $this->getPublicationDate()->format('d.m.Y'),
        (is_null($this->getDeadlineDate())) ? '' : $this->getDeadlineDate()->format('d.m.Y'),
        (is_null($this->getDateOfSummingUp())) ? '' : $this->getDateOfSummingUp()->format('d.m.Y'),
        $this->getPurchaseLink(),
        $this->getPurchaseNumber(),
        (is_null($this->getpostponementDate())) ? '' : $this->getpostponementDate()->format('d.m.Y'),
        $this->getPostonementComment(),
        (is_null($this->getDateOfConclusion())) ? '' : $this->getDateOfConclusion()->format('d.m.Y'),
        $this->getSupplierName(),
        $this->getSupplierINN(),
        $this->getSupplierKPP(),
        null,
        $this->getfinFederalFunds(),
        $this->getfinFundsOfSubject(),
        $this->getfinFundsOfEducationalOrg(),
        $this->getfinEmployersFunds(),
        (is_null($this->getDeliveryTime())) ? '' : $this->getDeliveryTime()->format('d.m.Y'),
        $this->getComments()
        );


        return $row;
    }

}
