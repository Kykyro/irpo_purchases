<?php

namespace App\Entity;

use App\Repository\PurchasesDumpDataRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @ORM\Entity(repositoryClass=PurchasesDumpDataRepository::class)
 */
class PurchasesDumpData
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
    private $dump;

//    private $serializer;
//
//    public function __construct(SerializerInterface $serializer)
//    {
//        $this->serializer = $serializer;
//    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDump(): ?string
    {
        return $this->dump;
    }

    public function setDump(?string $dump): self
    {
        $this->dump = $dump;

        return $this;
    }

    public function getNMCK(){
        $arr = $this->serializer->deserialize($this->getDump(), 'App\Entity\ProcurementProcedures[]' , 'json');
        $sum = 0;
        foreach ($arr as $item){
            $sum += $item->getNMCK();
        }
        return $sum;
    }

    public  function getFinSum(){
        $arr = $this->serializer->deserialize($this->getDump(), 'App\Entity\ProcurementProcedures[]' , 'json');
        $sum = 0;
        foreach ($arr as $item){
            $sum += $item->getContractCost();
        }
        return $sum;
    }

    public function getInitialBudget(SerializerInterface $serializer)
    {
        $arr = $serializer->deserialize($this->dump, 'App\Entity\ProcurementProcedures[]' , 'json');
        $sum = [
            'FederalFunds'  => 0,
            'FundsOfSubject' => 0,
            'EmployersFunds' => 0,
            'EducationalOrgFunds' => 0
        ];

        foreach ($arr as $item){
            $sum['FederalFunds'] += $item->getInitialFederalFunds();
            $sum['FundsOfSubject'] += $item->getInitialFundsOfSubject();
            $sum['EmployersFunds'] += $item->getInitialEmployersFunds();
            $sum['EducationalOrgFunds'] += $item->getInitialEducationalOrgFunds();
        }

        return $sum;
    }
    public function getFinBudget(SerializerInterface $serializer)
    {
        $arr = $serializer->deserialize($this->dump, 'App\Entity\ProcurementProcedures[]' , 'json');
        $sum = [
            'FederalFunds'  => 0,
            'FundsOfSubject' => 0,
            'EmployersFunds' => 0,
            'EducationalOrgFunds' => 0
        ];

        foreach ($arr as $item){
            $sum['FederalFunds'] += $item->getFinFederalFunds();
            $sum['FundsOfSubject'] += $item->getFinFundsOfSubject();
            $sum['EmployersFunds'] += $item->getFinEmployersFunds();
            $sum['EducationalOrgFunds'] += $item->getFinFundsOfEducationalOrg();
        }

        return $sum;
    }
}
