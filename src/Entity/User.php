<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"uuid"}, message="There is already an account with this uuid")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="json")
     *
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Name;

    /**
     * @ORM\OneToOne(targetEntity=UserInfo::class, cascade={"persist", "remove"})
     */
    private $user_info;

    /**
     * @ORM\OneToMany(targetEntity=FavoritesClusters::class, mappedBy="inspectorId")
     */
    private $clustersId;

    /**
     * @ORM\OneToMany(targetEntity=PurchasesDump::class, mappedBy="user")
     */
    private $purchasesDumps;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseNote::class, mappedBy="curator")
     */
    private $purchaseNotes;

    /**
     * @ORM\OneToMany(targetEntity=ClusterAddresses::class, mappedBy="user")
     */
    private $clusterAddresses;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }


    function __construct()
    {
        $this->setUserInfo(new UserInfo());
        $this->clustersId = new ArrayCollection();
        $this->purchasesDumps = new ArrayCollection();
        $this->purchaseNotes = new ArrayCollection();
        $this->clusterAddresses = new ArrayCollection();
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->uuid;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }



    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getUserInfo(): ?UserInfo
    {
        return $this->user_info;
    }

    public function setUserInfo(?UserInfo $user_info): self
    {
        $this->user_info = $user_info;

        return $this;
    }

    /**
     * @return Collection<int, FavoritesClusters>
     */
    public function getClustersId(): Collection
    {
        return $this->clustersId;
    }

    public function addClustersId(FavoritesClusters $clustersId): self
    {
        if (!$this->clustersId->contains($clustersId)) {
            $this->clustersId[] = $clustersId;
            $clustersId->setInspectorId($this);
        }

        return $this;
    }

    public function removeClustersId(FavoritesClusters $clustersId): self
    {
        if ($this->clustersId->removeElement($clustersId)) {
            // set the owning side to null (unless already changed)
            if ($clustersId->getInspectorId() === $this) {
                $clustersId->setInspectorId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PurchasesDump>
     */
    public function getPurchasesDumps(): Collection
    {
        return $this->purchasesDumps;
    }

    public function addPurchasesDump(PurchasesDump $purchasesDump): self
    {
        if (!$this->purchasesDumps->contains($purchasesDump)) {
            $this->purchasesDumps[] = $purchasesDump;
            $purchasesDump->setUser($this);
        }

        return $this;
    }

    public function removePurchasesDump(PurchasesDump $purchasesDump): self
    {
        if ($this->purchasesDumps->removeElement($purchasesDump)) {
            // set the owning side to null (unless already changed)
            if ($purchasesDump->getUser() === $this) {
                $purchasesDump->setUser(null);
            }
        }

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
            $purchaseNote->setCurator($this);
        }

        return $this;
    }

    public function removePurchaseNote(PurchaseNote $purchaseNote): self
    {
        if ($this->purchaseNotes->removeElement($purchaseNote)) {
            // set the owning side to null (unless already changed)
            if ($purchaseNote->getCurator() === $this) {
                $purchaseNote->setCurator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClusterAddresses>
     */
    public function getClusterAddresses(): Collection
    {
        return $this->clusterAddresses;
    }

    public function addClusterAddress(ClusterAddresses $clusterAddress): self
    {
        if (!$this->clusterAddresses->contains($clusterAddress)) {
            $this->clusterAddresses[] = $clusterAddress;
            $clusterAddress->setUser($this);
        }

        return $this;
    }

    public function removeClusterAddress(ClusterAddresses $clusterAddress): self
    {
        if ($this->clusterAddresses->removeElement($clusterAddress)) {
            // set the owning side to null (unless already changed)
            if ($clusterAddress->getUser() === $this) {
                $clusterAddress->setUser(null);
            }
        }

        return $this;
    }

    public  function getMidRepairByZone()
    {

        $count = count($this->clusterAddresses);
        $result = 0;
        foreach ($this->clusterAddresses as $address)
        {
            $result += $address->getMidRepairByZone();
        }


        if($count > 0)
            return $result/$count;
        else
            return 0;
    }
    public  function getMidRepairByCommon()
    {

        $count = count($this->clusterAddresses);
        $result = 0;
        foreach ($this->clusterAddresses as $address)
        {
            $result += $address->getMidRepairByCommon();
        }

        if($count > 0)
            return $result/$count;
        else
            return 0;
    }
    public function getEquipmentDeliveryDeadline()
    {
        $arr = [];
        foreach ($this->clusterAddresses as $address)
        {
            if($address->getEquipmentDeliveryDeadline())
                array_push($arr, $address->getEquipmentDeliveryDeadline());
        }
        usort($arr, function($a, $b) {
            $dateTimestamp1 = $a;
            $dateTimestamp2 = $b;

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });
        if(count($arr) > 0)
            return $arr[count($arr)-1]->format('d.m.Y');
        else
            return null;
    }
    public function getDeadlineForCompletionOfRepairs()
    {
        $arr = [];
        foreach ($this->clusterAddresses as $address)
        {
            if($address->getDeadlineForCompletionOfRepairs())
                array_push($arr, $address->getDeadlineForCompletionOfRepairs());
        }
        usort($arr, function($a, $b) {
            $dateTimestamp1 = $a;
            $dateTimestamp2 = $b;

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });
        if(count($arr) > 0)
            return $arr[count($arr)-1]->format('d.m.Y');
        else
            return null;


    }
    public function getCountOfEquipment()
    {
        $addreses = $this->getClusterAddresses();
        $arr = [
            'total' => 0,
            'fact' => 0,
            'putInOperation' => 0
        ];

        foreach ($addreses as $addres)
        {
            $equipment = $addres->getCountOfEquipment();
            if($equipment)
                foreach ($equipment as $key => $value)
                {
                    $arr[$key] += $value;
                }
        }

        return $arr;
    }
    public  function getCountOfWorkZone()
    {
        $addreses = $this->getClusterAddresses();
        $count = 0;

        foreach ($addreses as $addres)
        {
            $count += $addres->getCountOfWorkZone();

        }

        return $count;
    }
    public function getZones()
    {
        $addreses = $this->getClusterAddresses();
        $arr = [];

        foreach ($addreses as $addrese)
        {
            $arr = array_merge($arr, $addrese->getSortedClusterZones());
        }



        return $arr;
    }

    public function getSortedZones(){
        $clusters = $this->getZones();

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
}
