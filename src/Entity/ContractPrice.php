<?php

namespace App\Entity;

use App\Repository\ContractPriceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContractPriceRepository::class)
 */
class ContractPrice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $federal_funds;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $funds_of_the_subject;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $employers_funds;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $educational_organization_funds;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFederalFunds(): ?string
    {
        return $this->federal_funds;
    }

    public function setFederalFunds(?float $federal_funds): self
    {
        $this->federal_funds = $federal_funds;

        return $this;
    }

    public function getFundsOfTheSubject(): ?string
    {
        return $this->funds_of_the_subject;
    }

    public function setFundsOfTheSubject(?string $funds_of_the_subject): self
    {
        $this->funds_of_the_subject = $funds_of_the_subject;

        return $this;
    }

    public function getEmployersFunds(): ?string
    {
        return $this->employers_funds;
    }

    public function setEmployersFunds(?string $employers_funds): self
    {
        $this->employers_funds = $employers_funds;

        return $this;
    }

    public function getEducationalOrganizationFunds(): ?string
    {
        return $this->educational_organization_funds;
    }

    public function setEducationalOrganizationFunds(?string $educational_organization_funds): self
    {
        $this->educational_organization_funds = $educational_organization_funds;

        return $this;
    }
}
