<?php

namespace App\Entity;

use App\Repository\ProcurementProceduresRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Annotations\Log;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProcurementProceduresRepository::class)
 */
class ProcurementProcedures
{
    /**
     * @Groups("dump_data")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="text", nullable=true)
     * @Log
     */
    private $PurchaseObject;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $MethodOfDetermining;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $PurchaseLink;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="text", nullable=true)
     * @Log
     */
    private $PurchaseNumber;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $DateOfConclusion;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $DeliveryTime;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="text", nullable=true)
     * @Log
     */
    private $Comments;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $fileDir;

    /**
     *
     * @ORM\ManyToOne(targetEntity=user::class)
     * @Log
     */
    private $user;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $initialFederalFunds;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $initialFundsOfSubject;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $initialEmployersFunds;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $initialEducationalOrgFunds;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $supplierName;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $supplierINN;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $supplierKPP;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $finFederalFunds;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $finFundsOfSubject;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $finEmployersFunds;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $finFundsOfEducationalOrg;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $publicationDate;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $deadlineDate;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $dateOfSummingUp;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $postponementDate;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="text", nullable=true)
     * @Log
     */
    private $postonementComment;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="boolean", options={"default" : 0}, nullable=true)
     */
    private $isDeleted;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createDate;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $changeTime;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $version;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPlanned;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isHasPrepayment;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prepayment;

    /**
     * @Groups("dump_data")
     * @ORM\ManyToOne(targetEntity=ContractStatus::class)
     */
    private $conractStatus;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $factFederalFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $factFundsOfSubject;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $factEmployersFunds;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $factFundsOfEducationalOrg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $closingDocument;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentOrder;

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
        if($this->getMethodOfDetermining() == "Единственный поставщик"){
            array_push($row,
                $this->getPurchaseObject(),
                $this->getMethodOfDetermining(),
                $this->getNMCK(),
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
                $this->getNMCK(),
                $this->getInitialFederalFunds(),
                $this->getInitialFundsOfSubject(),
                $this->getInitialEmployersFunds(),
                $this->getInitialEducationalOrgFunds(),
                (is_null($this->getDeliveryTime())) ? '' : $this->getDeliveryTime()->format('d.m.Y'),
                $this->getComments()
            );
        }
        else{
            array_push($row,
                $this->getPurchaseObject(),
                $this->getMethodOfDetermining(),
                $this->getNMCK(),
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
                $this->getContractCost(),
                $this->getfinFederalFunds(),
                $this->getfinFundsOfSubject(),
                $this->getfinEmployersFunds(),
                $this->getfinFundsOfEducationalOrg(),
                (is_null($this->getDeliveryTime())) ? '' : $this->getDeliveryTime()->format('d.m.Y'),
                $this->getComments()
            );
        }



        return $row;
    }

    public function getAsRowWithFactFunds(){
        $row = [];
        if($this->getMethodOfDetermining() == "Единственный поставщик"){
            array_push($row,
                $this->getPurchaseObject(),
                $this->getMethodOfDetermining(),
                $this->getNMCK(),
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
                $this->getNMCK(),
                $this->getInitialFederalFunds(),
                $this->getInitialFundsOfSubject(),
                $this->getInitialEmployersFunds(),
                $this->getInitialEducationalOrgFunds(),
                (is_null($this->getDeliveryTime())) ? '' : $this->getDeliveryTime()->format('d.m.Y'),
                $this->getFactFederalFunds(),
                $this->getFactFundsOfSubject(),
                $this->getFactEmployersFunds(),
                $this->getFactFundsOfEducationalOrg(),
                $this->getComments()
            );
        }
        else{
            array_push($row,
                $this->getPurchaseObject(),
                $this->getMethodOfDetermining(),
                $this->getNMCK(),
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
                $this->getContractCost(),
                $this->getfinFederalFunds(),
                $this->getfinFundsOfSubject(),
                $this->getfinEmployersFunds(),
                $this->getfinFundsOfEducationalOrg(),
                (is_null($this->getDeliveryTime())) ? '' : $this->getDeliveryTime()->format('d.m.Y'),
                $this->getFactFederalFunds(),
                $this->getFactFundsOfSubject(),
                $this->getFactEmployersFunds(),
                $this->getFactFundsOfEducationalOrg(),
                $this->getComments()
            );
        }



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
        if($this->getMethodOfDetermining() == 'Единственный поставщик')
        {
            $sum = $this->getNMCK();
        }
        else{
            $sum = $this->finEmployersFunds + $this->finFederalFunds +
                $this->finFundsOfEducationalOrg + $this->finFundsOfSubject;
        }

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
            $source = $source."ФБ/ ";
        }
        if($this->initialFundsOfSubject > 0){
            $source = $source."РБ/ ";
        }
        if($this->initialEmployersFunds > 0){
            $source = $source."РД/ ";
        }
        if($this->initialEducationalOrgFunds > 0){
            $source = $source."ОО/ ";
        }

        return $source;
    }

    public function getIsPlanned(): ?bool
    {
        return $this->isPlanned;
    }

    public function setIsPlanned(?bool $isPlanned): self
    {
        $this->isPlanned = $isPlanned;

        return $this;
    }

    public function isIsHasPrepayment(): ?bool
    {
        return $this->isHasPrepayment;
    }

    public function setIsHasPrepayment(?bool $isHasPrepayment): self
    {
        $this->isHasPrepayment = $isHasPrepayment;

        return $this;
    }

    public function getPrepayment(): ?int
    {
        return $this->prepayment;
    }

    public function setPrepayment(?int $prepayment): self
    {
        $this->prepayment = $prepayment;

        return $this;
    }

    public function getConractStatus(): ?ContractStatus
    {
        return $this->conractStatus;
    }

    public function setConractStatus(?ContractStatus $conractStatus): self
    {
        $this->conractStatus = $conractStatus;

        return $this;
    }

    public function getFactFederalFunds(): ?string
    {
        return $this->factFederalFunds;
    }

    public function setFactFederalFunds(?string $factFederalFunds): self
    {
        $this->factFederalFunds = $factFederalFunds;

        return $this;
    }

    public function getFactFundsOfSubject(): ?string
    {
        return $this->factFundsOfSubject;
    }

    public function setFactFundsOfSubject(?string $factFundsOfSubject): self
    {
        $this->factFundsOfSubject = $factFundsOfSubject;

        return $this;
    }

    public function getFactEmployersFunds(): ?string
    {
        return $this->factEmployersFunds;
    }

    public function setFactEmployersFunds(?string $factEmployersFunds): self
    {
        $this->factEmployersFunds = $factEmployersFunds;

        return $this;
    }

    public function getFactFundsOfEducationalOrg(): ?string
    {
        return $this->factFundsOfEducationalOrg;
    }

    public function setFactFundsOfEducationalOrg(?string $factFundsOfEducationalOrg): self
    {
        $this->factFundsOfEducationalOrg = $factFundsOfEducationalOrg;

        return $this;
    }

    public function getClosingDocument(): ?string
    {
        return $this->closingDocument;
    }

    public function setClosingDocument(?string $closingDocument): self
    {
        $this->closingDocument = $closingDocument;

        return $this;
    }

    public function getPaymentOrder(): ?string
    {
        return $this->paymentOrder;
    }

    public function setPaymentOrder(?string $paymentOrder): self
    {
        $this->paymentOrder = $paymentOrder;

        return $this;
    }
}
