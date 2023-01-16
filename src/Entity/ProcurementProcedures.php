<?php

namespace App\Entity;

use App\Repository\ProcurementProceduresRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Annotations\Log;
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
     * @Log
     */
    private $PurchaseObject;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $MethodOfDetermining;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $PurchaseLink;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Log
     */
    private $PurchaseNumber;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $DateOfConclusion;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $DeliveryTime;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Log
     */
    private $Comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $fileDir;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     * @Log
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $initialFederalFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $initialFundsOfSubject;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $initialEmployersFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $initialEducationalOrgFunds;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $supplierName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $supplierINN;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $supplierKPP;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $finFederalFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $finFundsOfSubject;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $finEmployersFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $finFundsOfEducationalOrg;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $deadlineDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $dateOfSummingUp;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $postponementDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Log
     */
    private $postonementComment;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0}, nullable=true)
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $changeTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $version;

    function __construct() {
        $this->setIsDeleted(false);
        $this->setCreateDate(new \DateTime('@'.strtotime('now')));
        $this->setVersion(1);
    }

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

    public function getPurchaseNumber(): ?string
    {
        return $this->PurchaseNumber;
    }

    public function setPurchaseNumber(?string $PurchaseNumber): self
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
        $this->getInitialEducationalOrgFunds(),
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
        $this->getfinEmployersFunds(),
        $this->getfinFundsOfEducationalOrg(),
        (is_null($this->getDeliveryTime())) ? '' : $this->getDeliveryTime()->format('d.m.Y'),
        $this->getComments()
        );


        return $row;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(?\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getChangeTime(): ?\DateTimeInterface
    {
        return $this->changeTime;
    }

    public function setChangeTime(?\DateTimeInterface $changeTime): self
    {
        $this->changeTime = $changeTime;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): self
    {
        $this->version = $version;

        return $this;
    }
    public function getVersionInfoAndDate(): array
    {
        $arr = [
            'createDate' => is_null($this->getCreateDate()) ? '' : $this->getCreateDate()->format('r'),
            'changeTime' => is_null($this->getChangeTime()) ? '' : $this->getChangeTime()->format('r'),
            'version' => $this->getVersion()
        ];
        return $arr;
    }
    public function UpdateVersion()
    {
        if(is_null($this->version))
        {
            $this->version = 1;
        }
        else{
            $this->version += 1;
        }
    }

    public function getNMCK(){
        $sum = $this->initialFederalFunds + $this->initialFundsOfSubject +
                $this->initialEmployersFunds + $this->initialEducationalOrgFunds;
        if($sum > 0){
            return $sum;
        }
        else{
            return 0;
        }
    }

    public function getContractCost(){
        $sum = $this->finEmployersFunds + $this->finFederalFunds +
            $this->finFundsOfEducationalOrg + $this->finFundsOfSubject;
        if($sum > 0){
            return $sum;
        }
        else{
            return 0;
        }
    }

    public function getSourceOfFunding(){
        $source = "";

        if($this->initialFederalFunds > 0){
            $source = $source."средства федерального бюджета/ ";
        }
        if($this->initialFundsOfSubject > 0){
            $source = $source."средства субъекта РФ/ ";
        }
        if($this->initialEmployersFunds > 0){
            $source = $source."средства работодателей/ ";
        }
        if($this->initialEducationalOrgFunds > 0){
            $source = $source."средства образовательной организации/ ";
        }

        return $source;
    }
}
