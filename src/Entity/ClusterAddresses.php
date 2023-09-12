<?php

namespace App\Entity;

use App\Repository\ClusterAddressesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClusterAddressesRepository::class)
 */
class ClusterAddresses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addresses;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="clusterAddresses")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ClusterZone::class, mappedBy="addres")
     */
    private $clusterZones;

    public function __construct()
    {
        $this->clusterZones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddresses(): ?string
    {
        return $this->addresses;
    }

    public function setAddresses(?string $addresses): self
    {
        $this->addresses = $addresses;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, ClusterZone>
     */
    public function getClusterZones(): Collection
    {

        return $this->clusterZones;
    }

    public function getSortedClusterZones()
    {
        $clusters = $this->clusterZones;
        $common = [];
        $work = [];

        foreach ($clusters as $cluster)
        {
            if($cluster->getType()->getName() == 'Зона по видам работ')
            {
                array_push($work, $cluster);
            }
            else
            {
                array_push($common, $cluster);
            }
        }

        if(count($work) > 0)
        {
            for($i = 0; $i < count($work); $i++)
            {
                for($j = 0; $j < count($work); $j++)
                {
                    if(substr($work[$i]->getName(), 0,strpos($work[$i]->getName(), ' ')) < substr($work[$j]->getName(), 0, strpos($work[$j]->getName(), ' ')))
                    {
                        $_work = $work[$i];
                        $work[$i] = $work[$j];
                        $work[$j] = $_work;
                    }
                }
            }
        }




        return array_merge($common, $work);
    }

    public function getSortedClusterCommonZones()
    {
        $clusters = $this->clusterZones;
        $common = [];
        $work = [];

        foreach ($clusters as $cluster)
        {
            if($cluster->getType()->getName() == 'Зона по видам работ')
            {
//                array_push($work, $cluster);
            }
            else
            {
                array_push($common, $cluster);
            }
        }

        if(count($work) > 0)
        {
            for($i = 0; $i < count($work); $i++)
            {
                for($j = 0; $j < count($work); $j++)
                {
                    if(substr($work[$i]->getName(), 0,strpos($work[$i]->getName(), ' ')) < substr($work[$j]->getName(), 0, strpos($work[$j]->getName(), ' ')))
                    {
                        $_work = $work[$i];
                        $work[$i] = $work[$j];
                        $work[$j] = $_work;
                    }
                }
            }
        }




        return array_merge($common, $work);
    }

    public function addClusterZone(ClusterZone $clusterZone): self
    {
        if (!$this->clusterZones->contains($clusterZone)) {
            $this->clusterZones[] = $clusterZone;
            $clusterZone->setAddres($this);
        }

        return $this;
    }

    public function removeClusterZone(ClusterZone $clusterZone): self
    {
        if ($this->clusterZones->removeElement($clusterZone)) {
            // set the owning side to null (unless already changed)
            if ($clusterZone->getAddres() === $this) {
                $clusterZone->setAddres(null);
            }
        }

        return $this;
    }

    public  function getMidRepairByZone()
    {
        $zones = $this->getClusterZones();
        $count = 0;
        $result = 0;
        foreach ($zones as $zone)
        {
            if($zone->getType()->getName() == "Зона по видам работ" and !$zone->isDoNotTake())
            {
                $result += $zone->getZoneRepair()->getTotalPercentage();
                $count++;
            }

        }

        if($count > 0)
            return $result/$count;
        else
            return 0;
    }
    public  function getMidRepairByCommon()
    {
        $zones = $this->getClusterZones();
        $count = 0;
        $result = 0;
        foreach ($zones as $zone)
        {
            $zoneType = $zone->getType()->getName();
            if($zoneType != "Зона по видам работ" and $zoneType != "Иное")
            {
                $result += $zone->getZoneRepair()->getTotalPercentage();
                $count++;
            }

        }

        if($count > 0)
            return $result/$count;
        else
            return 0;
    }
    public  function getCountOfWorkZone()
    {
        $zones = $this->getClusterZones();
        $count = 0;

        foreach ($zones as $zone)
        {
            $zoneType = $zone->getType()->getName();
            if($zoneType == "Зона по видам работ" and !$zone->isDoNotTake())
            {
                $count++;
            }

        }

        return $count;
    }
    public function getEquipmentDeliveryDeadline()
    {
        $zones = $this->getClusterZones();
        if(count($zones) > 0)
        {
            $date = $zones[0]->getMaxEquipmentDeliveryDeadline();
            foreach ($zones as $zone) {
                $_date = $zone->getMaxEquipmentDeliveryDeadline();
                if($date < $_date)
                {
                    $date = $_date;
                }
            }

            return $date;
        }
        else
        {
            return null;
        }


    }
    public function getMinEquipmentDeliveryDeadline()
    {
        $zones = $this->getClusterZones();
        if(count($zones) > 0)
        {
            $date = $zones[0]->getMinEquipmentDeliveryDeadline();
            foreach ($zones as $zone) {
                $_date = $zone->getMinEquipmentDeliveryDeadline();
                if($date > $_date and $_date != null)
                {
                    $date = $_date;
                }
            }

            return $date;
        }
        else
        {
            return null;
        }


    }
    public function getDeadlineForCompletionOfRepairs()
    {
        $zones = $this->getClusterZones();
        if(count($zones) > 0)
        {
            $date = $zones[0]->getZoneRepair()->getEndDate();

            foreach ($zones as $zone)
            {
                $_date = $zone->getZoneRepair()->getEndDate();
                if($date < $_date)
                {
                    $date = $_date;
                }

            }
            return $date;

        }
        else
        {
            return null;
        }


    }

    public function getCountOfEquipment()
    {
        $zones = $this->getClusterZones();
        $arr = [
            'total' => 0,
            'fact' => 0,
            'putInOperation' => 0
        ];

        foreach ($zones as $zone)
        {
            $equipment = $zone->getCountOfEquipment();
            if($equipment)
                foreach ($equipment as $key => $value)
                {
                    $arr[$key] += $value;
                }
        }

        return $arr;
    }
    public function getRepairDump($group, $zone)
    {

        $repair = $zone->getZoneRepair();

        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('repairDumpGroup', $group))
            ->orderBy(['createdAt' => 'DESC'])
        ;

//        dd( $repair->getRepairDumps()->matching($criteria));
        return $repair->getRepairDumps()->matching($criteria);


    }
}
