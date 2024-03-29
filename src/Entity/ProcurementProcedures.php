<?php

namespace App\Entity;

use App\Repository\ProcurementProceduresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="text", nullable=true)
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
     * @Log
     */
    private $isPlanned;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="boolean", nullable=true)
     * @Log
     */
    private $isHasPrepayment;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="integer", nullable=true)
     * @Log
     */
    private $prepayment;

    /**
     * @Groups("dump_data")
     * @ORM\ManyToOne(targetEntity=ContractStatus::class)
     */
    private $conractStatus;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $factFederalFunds;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $factFundsOfSubject;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $factEmployersFunds;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     * @Log
     */
    private $factFundsOfEducationalOrg;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $closingDocument;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Log
     */
    private $paymentOrder;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $deleteReason;

    /**
     * @Log
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalAgreement;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="boolean", nullable=true)
     * @Log
     */
    private $hasAdditionalAgreement;

    /**
     * @Groups("dump_data")
     * @ORM\OneToMany(targetEntity=PurchaseNote::class, mappedBy="purchase")
     *
     */
    private $purchaseNotes;

    /**
     * @Groups("dump_data")
     * @ORM\Column(type="date", nullable=true)
     * @Log
     */
    private $prepaymentDate;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     * @Log
     * @Groups("dump_data")
     */
    private $isCancelled;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Log
     * @Groups("dump_data")
     */
    private $cancelledComment;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $plannedPublicationDate;

    /**
     * @ORM\OneToMany(targetEntity=AnotherDocument::class, mappedBy="purchases")
     */
    private $anotherDocuments;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRead;

    function __construct() {
        $this->setIsDeleted(false);
        $this->setCreateDate(new \DateTime('@'.strtotime('now')));
        $this->setVersion(1);
        $this->purchaseNotes = new ArrayCollection();
        $this->anotherDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Получить предмет закупки
     *
     * @return null|string
     */
    public function getPurchaseObject(): ?string
    {
        return $this->PurchaseObject;
    }

    public function setPurchaseObject(?string $PurchaseObject): self
    {
        $this->PurchaseObject = $PurchaseObject;

        return $this;
    }

    /**
     * Получить способ определение поставщика
     *
     * @return null|string
     */
    public function getMethodOfDetermining(): ?string
    {
        return $this->MethodOfDetermining;
    }

    public function setMethodOfDetermining(?string $MethodOfDetermining): self
    {
        $this->MethodOfDetermining = $MethodOfDetermining;

        return $this;
    }

    /**
     * Получить ссылку на закупку
     *
     * @return null|string
     */
    public function getPurchaseLink(): ?string
    {
        return $this->PurchaseLink;
    }

    public function setPurchaseLink(?string $PurchaseLink): self
    {
        $this->PurchaseLink = $PurchaseLink;

        return $this;
    }

    /**
     * Получить номер закупки
     *
     * @return null|string
     */
    public function getPurchaseNumber(): ?string
    {
        return $this->PurchaseNumber;
    }

    public function setPurchaseNumber(?string $PurchaseNumber): self
    {
        $this->PurchaseNumber = $PurchaseNumber;

        return $this;
    }

    /**
     * Получить дату заключения договора
     *
     * @return \DateTimeInterface|null
     */
    public function getDateOfConclusion(): ?\DateTimeInterface
    {
        return $this->DateOfConclusion;
    }

    public function setDateOfConclusion(?\DateTimeInterface $DateOfConclusion): self
    {
        $this->DateOfConclusion = $DateOfConclusion;

        return $this;
    }

    /**
     * Получить время поставки
     *
     * @return \DateTimeInterface|null
     */
    public function getDeliveryTime(): ?\DateTimeInterface
    {
        return $this->DeliveryTime;
    }

    public function setDeliveryTime(?\DateTimeInterface $DeliveryTime): self
    {
        $this->DeliveryTime = $DeliveryTime;

        return $this;
    }

    /**
     * Получить комментарий
     *
     * @return null|string
     */
    public function getComments(): ?string
    {
        return $this->Comments;
    }

    public function setComments(?string $Comments): self
    {
        $this->Comments = $Comments;

        return $this;
    }

    /**
     * Получить название файла Договор/ предмет договора
     *
     * @return null|string
     */
    public function getFileDir(): ?string
    {
        return $this->fileDir;
    }

    public function setFileDir(?string $fileDir): self
    {
        $this->fileDir = $fileDir;

        return $this;
    }

    /**
     * Получить пользователя (создателя закупки)
     *
     * @return user|null
     */
    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * Получить начальный ФБ
     *
     * @return null|string
     */
    public function getInitialFederalFunds(): ?string
    {
        return $this->initialFederalFunds;
    }

    public function setInitialFederalFunds(?string $initialFederalFunds): self
    {
        $this->initialFederalFunds = $initialFederalFunds;

        return $this;
    }

    /**
     * Получить начальный РБ
     *
     * @return null|string
     */
    public function getInitialFundsOfSubject(): ?string
    {
        return $this->initialFundsOfSubject;
    }

    public function setInitialFundsOfSubject(?string $initialFundsOfSubject): self
    {
        $this->initialFundsOfSubject = $initialFundsOfSubject;

        return $this;
    }

    /**
     * Получить начальный РД
     *
     * @return null|string
     */
    public function getInitialEmployersFunds(): ?string
    {
        return $this->initialEmployersFunds;
    }

    public function setInitialEmployersFunds(?string $initialEmployersFunds): self
    {
        $this->initialEmployersFunds = $initialEmployersFunds;

        return $this;
    }


    /**
     * Получить начальный ОО
     *
     * @return null|string
     */
    public function getInitialEducationalOrgFunds(): ?string
    {
        return $this->initialEducationalOrgFunds;
    }

    public function setInitialEducationalOrgFunds(?string $initialEducationalOrgFunds): self
    {
        $this->initialEducationalOrgFunds = $initialEducationalOrgFunds;

        return $this;
    }

    /**
     * Получить поставщика
     *
     * @return null|string
     */
    public function getSupplierName(): ?string
    {
        return $this->supplierName;
    }

    public function setSupplierName(?string $supplierName): self
    {
        $this->supplierName = $supplierName;

        return $this;
    }

    /**
     * Получть ИНН поставщика
     *
     * @return null|string
     */
    public function getSupplierINN(): ?string
    {
        return $this->supplierINN;
    }

    public function setSupplierINN(?string $supplierINN): self
    {
        $this->supplierINN = $supplierINN;

        return $this;
    }

    /**
     * Получить КПП поставщика
     *
     * @return null|string
     */
    public function getSupplierKPP(): ?string
    {
        return $this->supplierKPP;
    }

    public function setSupplierKPP(?string $supplierKPP): self
    {
        $this->supplierKPP = $supplierKPP;

        return $this;
    }

    /**
     * Получить цену контракта ФБ
     *
     * @return null|string
     */
    public function getFinFederalFunds(): ?string
    {
        if ($this->getMethodOfDetermining() == "Единственный поставщик" and !is_null($this->finFederalFunds))
            return $this->finFederalFunds;
        elseif ($this->getMethodOfDetermining() == "Единственный поставщик" and is_null($this->finFederalFunds))
            return $this->initialFederalFunds;
        else
            return $this->finFederalFunds;
    }

    public function setFinFederalFunds(?string $finFederalFunds): self
    {
        $this->finFederalFunds = $finFederalFunds;

        return $this;
    }

    public function getFinFundsOfSubject(): ?string
    {
        if ($this->getMethodOfDetermining() == "Единственный поставщик" and !is_null($this->finFundsOfSubject))
            return $this->finFundsOfSubject;
        elseif ($this->getMethodOfDetermining() == "Единственный поставщик" and is_null($this->finFundsOfSubject))
            return $this->initialFundsOfSubject;
        else
            return $this->finFundsOfSubject;
//        return $this->finFundsOfSubject;
    }

    public function setFinFundsOfSubject(?string $finFundsOfSubject): self
    {
        $this->finFundsOfSubject = $finFundsOfSubject;

        return $this;
    }

    public function getFinEmployersFunds(): ?string
    {
        if ($this->getMethodOfDetermining() == "Единственный поставщик" and !is_null($this->finEmployersFunds))
            return $this->finEmployersFunds;
        elseif ($this->getMethodOfDetermining() == "Единственный поставщик" and is_null($this->finEmployersFunds))
            return $this->initialEmployersFunds;
        else
            return $this->finEmployersFunds;
//        return $this->finEmployersFunds;
    }

    public function setFinEmployersFunds(?string $finEmployersFunds): self
    {
        $this->finEmployersFunds = $finEmployersFunds;

        return $this;
    }

    public function getFinFundsOfEducationalOrg(): ?string
    {
        if ($this->getMethodOfDetermining() == "Единственный поставщик" and !is_null($this->finFundsOfEducationalOrg))
            return $this->finFundsOfEducationalOrg;
        elseif ($this->getMethodOfDetermining() == "Единственный поставщик" and is_null($this->finFundsOfEducationalOrg))
            return $this->initialEducationalOrgFunds;
        else
            return $this->finFundsOfEducationalOrg;
//        return $this->finFundsOfEducationalOrg;
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

        array_push($row,
            $this->getPurchaseObject(),
            $this->getMethodOfDetermining(),
            $this->getNMCK(),
            ($this->getInitialFederalFunds() <= 0) ? '0' : $this->getInitialFederalFunds(),
            ($this->getInitialFundsOfSubject() <= 0) ? '0' : $this->getInitialFundsOfSubject(),
            ($this->getInitialEmployersFunds() <= 0) ? '0' : $this->getInitialEmployersFunds(),
            ($this->getInitialEducationalOrgFunds() <= 0) ? '0' : $this->getInitialEducationalOrgFunds(),
            (is_null($this->getPublicationDate())) ? (is_null($this->getPlannedPublicationDate())) ? '' : $this->getPlannedPublicationDate()->format('d.m.Y') : $this->getPublicationDate()->format('d.m.Y'),
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
            ($this->getfinFederalFunds() <= 0) ? '0' : $this->getfinFederalFunds(),
            ($this->getfinFundsOfSubject() <= 0) ? '0' : $this->getfinFundsOfSubject(),
            ($this->getfinEmployersFunds() <= 0) ? '0' : $this->getfinEmployersFunds(),
            ($this->getfinFundsOfEducationalOrg() <= 0) ? '0' : $this->getfinFundsOfEducationalOrg(),
            (is_null($this->getDeliveryTime())) ? '' : $this->getDeliveryTime()->format('d.m.Y'),
            ($this->getFactFederalFunds() <= 0) ? '0' : $this->getFactFederalFunds(),
            ($this->getFactFundsOfSubject() <= 0) ? '0' : $this->getFactFundsOfSubject(),
            ($this->getFactEmployersFunds() <= 0) ? '0' : $this->getFactEmployersFunds(),
            ($this->getFactFundsOfEducationalOrg() <= 0) ? '0' : $this->getFactFundsOfEducationalOrg(),
            ($this->isIsCancelled()) ? $this->getCancelledComment() : $this->getComments()
        );

        return $row;
    }

    public function getAsRowForBas(){
        $row = [];

        array_push($row,
            $this->getPurchaseObject(),
            $this->getMethodOfDetermining(),
            $this->getNMCK(),
            ($this->getInitialFederalFunds() <= 0) ? '0' : $this->getInitialFederalFunds(),
            ($this->getInitialFundsOfSubject() <= 0) ? '0' : $this->getInitialFundsOfSubject(),
            (is_null($this->getPublicationDate())) ? (is_null($this->getPlannedPublicationDate())) ? '' : $this->getPlannedPublicationDate()->format('d.m.Y') : $this->getPublicationDate()->format('d.m.Y'),
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
            ($this->getfinFederalFunds() <= 0) ? '0' : $this->getfinFederalFunds(),
            ($this->getfinFundsOfSubject() <= 0) ? '0' : $this->getfinFundsOfSubject(),

            (is_null($this->getDeliveryTime())) ? '' : $this->getDeliveryTime()->format('d.m.Y'),
            ($this->getFactFederalFunds() <= 0) ? '0' : $this->getFactFederalFunds(),
            ($this->getFactFundsOfSubject() <= 0) ? '0' : $this->getFactFundsOfSubject(),

            ($this->isIsCancelled()) ? $this->getCancelledComment() : $this->getComments()
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
        $sum = $this->getFinEmployersFunds() + $this->getFinFederalFunds() +
            $this->getFinFundsOfSubject() + $this->getFinFundsOfEducationalOrg();

        if($sum > 0){
            return $sum;
        }
        else{
            return 0;
        }
    }
    public function getFactCost(){
        $sum = $this->getFactEmployersFunds() + $this->getFactFederalFunds() +
            $this->getFactFundsOfSubject() + $this->getFactFundsOfEducationalOrg();

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
            $source = $source."ФБ / ";
        }
        if($this->initialFundsOfSubject > 0){
            $source = $source."РБ / ";
        }
        if($this->initialEmployersFunds > 0){
            $source = $source."РД / ";
        }
        if($this->initialEducationalOrgFunds > 0){
            $source = $source."ОО / ";
        }
        $source = trim($source);
        $source = substr($source,0,-1);
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

    public function getDeleteReason(): ?string
    {
        return $this->deleteReason;
    }

    public function setDeleteReason(?string $deleteReason): self
    {
        $this->deleteReason = $deleteReason;

        return $this;
    }

    public function getAdditionalAgreement(): ?string
    {
        return $this->additionalAgreement;
    }

    public function setAdditionalAgreement(?string $additionalAgreement): self
    {
        $this->additionalAgreement = $additionalAgreement;

        return $this;
    }

    public function isHasAdditionalAgreement(): ?bool
    {
        return $this->hasAdditionalAgreement;
    }

    public function setHasAdditionalAgreement(?bool $hasAdditionalAgreement): self
    {
        $this->hasAdditionalAgreement = $hasAdditionalAgreement;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseNote>
     */
    public function getPurchaseNotes(): Collection
    {
        return $this->purchaseNotes;
    }

    public function addPurchaseNote(PurchaseNote $purchaseNote): self
    {
        if (!$this->purchaseNotes->contains($purchaseNote)) {
            $this->purchaseNotes[] = $purchaseNote;
            $purchaseNote->setPurchase($this);
        }

        return $this;
    }

    public function removePurchaseNote(PurchaseNote $purchaseNote): self
    {
        if ($this->purchaseNotes->removeElement($purchaseNote)) {
            // set the owning side to null (unless already changed)
            if ($purchaseNote->getPurchase() === $this) {
                $purchaseNote->setPurchase(null);
            }
        }

        return $this;
    }

    public function getPrepaymentDate(): ?\DateTimeInterface
    {
        return $this->prepaymentDate;
    }

    public function setPrepaymentDate(?\DateTimeInterface $prepaymentDate): self
    {
        $this->prepaymentDate = $prepaymentDate;

        return $this;
    }

    public function getPurchasesStatus(\DateTime $day)
    {
        if($this->isIsCancelled())
            return 'cancelled';

        $day = $day->setTime(0,0,0,0);
        if($this->getMethodOfDetermining() == 'Единственный поставщик')
        {
            if(is_null($this->getDateOfConclusion()))
                return 'planning'; // планируется
            if($this->getDateOfConclusion() > $day)
                return 'planning'; // планируется
            else
                return 'contract'; // Закантрактовано

        }
        else
        {

//            print '<pre>'; var_dump(filter_var($url, FILTER_VALIDATE_URL));
//            dd(filter_var('https://vk.com?a=да', FILTER_VALIDATE_URL));
            if(is_null($this->getPurchaseLink()) or !$this->validateUrl($this->getPurchaseLink()))
                return 'planning'; // планируется

            if (is_null($this->getPublicationDate()) or $this->getPublicationDate() > $day){
                return 'planning'; // планируется
            }
            else{
                if(is_null($this->getDateOfConclusion()))
                {
                    if ($this->getPublicationDate() <= $day)
                        return 'announced'; // Объявлено
                }
                else
                {
                    if($this->getDateOfConclusion() <= $day)
                        return 'contract'; // закантрактовано

                    if ($this->getDateOfConclusion() > $day and $this->getPublicationDate() <= $day)
                        return 'announced'; // Объявлено
                }



                return 'planning';
            }
        }
    }

    public function validateUrl($url)
    {
        $url = urlencode($url);
        $url = str_replace(array('%3A', '%2F'), array(':', '/'), $url);

        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public function isIsCancelled(): ?bool
    {
        return $this->isCancelled;
    }

    public function setIsCancelled(?bool $isCancelled): self
    {
        $this->isCancelled = $isCancelled;

        return $this;
    }

    public function getCancelledComment(): ?string
    {
        return $this->cancelledComment;
    }

    public function setCancelledComment(?string $cancelledComment): self
    {
        $this->cancelledComment = $cancelledComment;

        return $this;
    }

    public function getPlannedPublicationDate(): ?\DateTimeInterface
    {
        return $this->plannedPublicationDate;
    }

    public function setPlannedPublicationDate(?\DateTimeInterface $plannedPublicationDate): self
    {
        $this->plannedPublicationDate = $plannedPublicationDate;

        return $this;
    }

    /**
     * @return Collection<int, AnotherDocument>
     */
    public function getAnotherDocuments(): Collection
    {
        return $this->anotherDocuments;
    }

    public function addAnotherDocument(AnotherDocument $anotherDocument): self
    {
        if (!$this->anotherDocuments->contains($anotherDocument)) {
            $this->anotherDocuments[] = $anotherDocument;
            $anotherDocument->setPurchases($this);
        }

        return $this;
    }

    public function removeAnotherDocument(AnotherDocument $anotherDocument): self
    {
        if ($this->anotherDocuments->removeElement($anotherDocument)) {
            // set the owning side to null (unless already changed)
            if ($anotherDocument->getPurchases() === $this) {
                $anotherDocument->setPurchases(null);
            }
        }

        return $this;
    }

    public function isHasFiles()
    {
        if($this->getFileDir())
            return true;
        if($this->getAdditionalAgreement())
            return true;
        if(count($this->getAnotherDocuments()) > 0)
            return true;
        if($this->getPaymentOrder())
            return true;

        return false;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(?bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }
}
