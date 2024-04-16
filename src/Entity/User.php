<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
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

    /**
     * @ORM\OneToMany(targetEntity=ReadinessMapArchive::class, mappedBy="user")
     */
    private $readinessMapArchives;

    /**
     * @ORM\OneToMany(targetEntity=RepairDump::class, mappedBy="user")
     */
    private $repairDumps;

    /**
     * @ORM\ManyToMany(targetEntity=UserTags::class, mappedBy="users")
     */
    private $userTags;

    /**
     * @ORM\OneToMany(targetEntity=SheetWorkzone::class, mappedBy="user")
     */
    private $sheetWorkzones;

    /**
     * @ORM\OneToMany(targetEntity=ApiToken::class, mappedBy="user")
     */
    private $apiTokens;

    /**
     * @ORM\OneToMany(targetEntity=UsersEvents::class, mappedBy="user")
     */
    private $usersEvents;

    /**
     * @ORM\OneToMany(targetEntity=ReadinessMapCheck::class, mappedBy="user")
     */
    private $readinessMapChecks;

    /**
     * @ORM\OneToMany(targetEntity=CompitenceProfile::class, mappedBy="user")
     */
    private $compitenceProfiles;

    /**
     * @ORM\OneToOne(targetEntity=CofinancingFunds::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $cofinancingFunds;

    /**
     * @ORM\OneToMany(targetEntity=CofinancingScenario::class, mappedBy="user")
     */
    private $cofinancingScenarios;
    

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
        $this->readinessMapArchives = new ArrayCollection();
        $this->repairDumps = new ArrayCollection();
        $this->userTags = new ArrayCollection();
        $this->sheetWorkzones = new ArrayCollection();
        $this->apiTokens = new ArrayCollection();
        $this->usersEvents = new ArrayCollection();
        $this->readinessMapChecks = new ArrayCollection();
        $this->compitenceProfiles = new ArrayCollection();
        $this->cofinancingScenarios = new ArrayCollection();
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

        $count = 0;
        $result = 0;
        foreach ($this->clusterAddresses as $address)
        {
            $zones = $address->getClusterZones();
            foreach ($zones as $zone)
            {
                if($zone->getType()->getName() == 'Зона по видам работ' and !$zone->isDoNotTake())
                {
                    $count++;
                    $result += $zone->getZoneRepair()->getTotalPercentage();
                }

            }

        }

        if($count > 0)
            return $result/$count;
        else
            return 0;
    }
    public  function getMidRepairByCommon()
    {

        $count = 0;
        $result = 0;
        $arr_type = [
          'Фасад' => 0,
          'Входная группа' => 0,
          'Холл (фойе)' => 0,
          'Коридоры' => 0,
          'Фасад_count' => 0,
          'Входная группа_count' => 0,
          'Холл (фойе)_count' => 0,
          'Коридоры_count' => 0,
        ];
        foreach ($this->clusterAddresses as $address)
        {
            $zones = $address->getClusterZones();
            foreach ($zones as $zone)
            {
                $zoneType = $zone->getType()->getName();
                if($zoneType != 'Зона по видам работ' and $zoneType != "Иное")
                {
                    if ($zoneType == 'Фасад')
                    {
                        $arr_type['Фасад'] += $zone->getZoneRepair()->getTotalPercentage();
                        $arr_type['Фасад_count']++;
                    }

                    if ($zoneType == 'Входная группа')
                    {
                        $arr_type['Входная группа'] += $zone->getZoneRepair()->getTotalPercentage();
                        $arr_type['Входная группа_count']++;
                    }
                    if ($zoneType == 'Холл (фойе)')
                    {
                        $arr_type['Холл (фойе)'] += $zone->getZoneRepair()->getTotalPercentage();
                        $arr_type['Холл (фойе)_count']++;
                    }
                    if ($zoneType == 'Коридоры')
                    {
                        $arr_type['Коридоры'] += $zone->getZoneRepair()->getTotalPercentage();
                        $arr_type['Коридоры_count']++;
                    }
                }

            }

        }
        $result = 0;
        if($arr_type['Фасад_count'] > 0)
        {
            $result += $arr_type['Фасад']/$arr_type['Фасад_count'];
            $count++;
        }

        if($arr_type['Входная группа_count'] > 0)
        {
            $result += $arr_type['Входная группа']/$arr_type['Входная группа_count'];
            $count++;
        }
        if($arr_type['Холл (фойе)_count'] > 0)
        {
            $result += $arr_type['Холл (фойе)']/$arr_type['Холл (фойе)_count'];
            $count++;
        }
        if($arr_type['Коридоры_count'] > 0)
        {
            $result += $arr_type['Коридоры']/$arr_type['Коридоры_count'];
            $count++;
        }
        if($count == 0 and in_array('ROLE_BAS', $this->getRoles()))
            return 100;
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
    public function getMinEquipmentDeliveryDeadline()
    {
        $arr = [];
        foreach ($this->clusterAddresses as $address)
        {
            if($address->getMinEquipmentDeliveryDeadline())
                array_push($arr, $address->getMinEquipmentDeliveryDeadline());
        }
        usort($arr, function($a, $b) {
            $dateTimestamp1 = $a;
            $dateTimestamp2 = $b;

            return ($dateTimestamp1 > $dateTimestamp2 ) ? -1: 1;
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

    public function getSortedWorkZones(){
        $clusters = $this->getZones();

        $common = [];
        $work = [];

        foreach ($clusters as $cluster)
        {
            if($cluster->getType()->getName() == 'Зона по видам работ')
            {
                array_push($work, $cluster);
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

    /**
     * @return Collection<int, ReadinessMapArchive>
     */
    public function getReadinessMapArchives(): Collection
    {
        return $this->readinessMapArchives;
    }

    public function addReadinessMapArchive(ReadinessMapArchive $readinessMapArchive): self
    {
        if (!$this->readinessMapArchives->contains($readinessMapArchive)) {
            $this->readinessMapArchives[] = $readinessMapArchive;
            $readinessMapArchive->setUser($this);
        }

        return $this;
    }

    public function removeReadinessMapArchive(ReadinessMapArchive $readinessMapArchive): self
    {
        if ($this->readinessMapArchives->removeElement($readinessMapArchive)) {
            // set the owning side to null (unless already changed)
            if ($readinessMapArchive->getUser() === $this) {
                $readinessMapArchive->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RepairDump>
     */
    public function getRepairDumps(): Collection
    {
        return $this->repairDumps;
    }

    public function addRepairDump(RepairDump $repairDump): self
    {
        if (!$this->repairDumps->contains($repairDump)) {
            $this->repairDumps[] = $repairDump;
            $repairDump->setUser($this);
        }

        return $this;
    }

    public function removeRepairDump(RepairDump $repairDump): self
    {
        if ($this->repairDumps->removeElement($repairDump)) {
            // set the owning side to null (unless already changed)
            if ($repairDump->getUser() === $this) {
                $repairDump->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserTags>
     */
    public function getUserTags(): Collection
    {
        return $this->userTags;
    }
    public function getUserTagsArray(): array
    {
        $arr = [];

        foreach ($this->userTags as $tag)
        {
            array_push($arr, $tag->getTag());
        }

        return $arr;
    }

    public function addUserTag(UserTags $userTag): self
    {
        if (!$this->userTags->contains($userTag)) {
            $this->userTags[] = $userTag;
            $userTag->addUser($this);
        }

        return $this;
    }

    public function removeUserTag(UserTags $userTag): self
    {
        if ($this->userTags->removeElement($userTag)) {
            $userTag->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SheetWorkzone>
     */
    public function getSheetWorkzones(): Collection
    {
        return $this->sheetWorkzones;
    }

    public function addSheetWorkzone(SheetWorkzone $sheetWorkzone): self
    {
        if (!$this->sheetWorkzones->contains($sheetWorkzone)) {
            $this->sheetWorkzones[] = $sheetWorkzone;
            $sheetWorkzone->setUser($this);
        }

        return $this;
    }

    public function removeSheetWorkzone(SheetWorkzone $sheetWorkzone): self
    {
        if ($this->sheetWorkzones->removeElement($sheetWorkzone)) {
            // set the owning side to null (unless already changed)
            if ($sheetWorkzone->getUser() === $this) {
                $sheetWorkzone->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApiToken>
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UsersEvents>
     */
    public function getUsersEvents(): Collection
    {
        return $this->usersEvents;
    }
    public function getUsersEventsNotDeleted(): Collection
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('deleted', false))
            ->orderBy(['finishDate' => 'ASC'])
        ;
        return $this->usersEvents->matching($criteria);
    }
    public function getUsersEventsByType(string $type): Collection
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('deleted', false))
            ->andWhere(Criteria::expr()->eq('type', $type))
            ->orderBy(['finishDate' => 'ASC'])
        ;
        return $this->usersEvents->matching($criteria);
    }

    public function addUsersEvent(UsersEvents $usersEvent): self
    {
        if (!$this->usersEvents->contains($usersEvent)) {
            $this->usersEvents[] = $usersEvent;
            $usersEvent->setUser($this);
        }

        return $this;
    }

    public function removeUsersEvent(UsersEvents $usersEvent): self
    {
        if ($this->usersEvents->removeElement($usersEvent)) {
            // set the owning side to null (unless already changed)
            if ($usersEvent->getUser() === $this) {
                $usersEvent->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReadinessMapCheck>
     */
    public function getReadinessMapChecks(): Collection
    {
        return $this->readinessMapChecks;
    }

    public function addReadinessMapCheck(ReadinessMapCheck $readinessMapCheck): self
    {
        if (!$this->readinessMapChecks->contains($readinessMapCheck)) {
            $this->readinessMapChecks[] = $readinessMapCheck;
            $readinessMapCheck->setUser($this);
        }

        return $this;
    }

    public function removeReadinessMapCheck(ReadinessMapCheck $readinessMapCheck): self
    {
        if ($this->readinessMapChecks->removeElement($readinessMapCheck)) {
            // set the owning side to null (unless already changed)
            if ($readinessMapCheck->getUser() === $this) {
                $readinessMapCheck->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CompitenceProfile>
     */
    public function getCompitenceProfiles(): Collection
    {
        return $this->compitenceProfiles;
    }

    public function addCompitenceProfile(CompitenceProfile $compitenceProfile): self
    {
        if (!$this->compitenceProfiles->contains($compitenceProfile)) {
            $this->compitenceProfiles[] = $compitenceProfile;
            $compitenceProfile->setUser($this);
        }

        return $this;
    }

    public function removeCompitenceProfile(CompitenceProfile $compitenceProfile): self
    {
        if ($this->compitenceProfiles->removeElement($compitenceProfile)) {
            // set the owning side to null (unless already changed)
            if ($compitenceProfile->getUser() === $this) {
                $compitenceProfile->setUser(null);
            }
        }

        return $this;
    }

    public function getCofinancingFunds(): ?CofinancingFunds
    {
        return $this->cofinancingFunds;
    }

    public function setCofinancingFunds(?CofinancingFunds $cofinancingFunds): self
    {
        // unset the owning side of the relation if necessary
        if ($cofinancingFunds === null && $this->cofinancingFunds !== null) {
            $this->cofinancingFunds->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($cofinancingFunds !== null && $cofinancingFunds->getUser() !== $this) {
            $cofinancingFunds->setUser($this);
        }

        $this->cofinancingFunds = $cofinancingFunds;

        return $this;
    }

    /**
     * @return Collection<int, CofinancingScenario>
     */
    public function getCofinancingScenarios(): Collection
    {
        return $this->cofinancingScenarios;
    }

    public function addCofinancingScenario(CofinancingScenario $cofinancingScenario): self
    {
        if (!$this->cofinancingScenarios->contains($cofinancingScenario)) {
            $this->cofinancingScenarios[] = $cofinancingScenario;
            $cofinancingScenario->setUser($this);
        }

        return $this;
    }

    public function removeCofinancingScenario(CofinancingScenario $cofinancingScenario): self
    {
        if ($this->cofinancingScenarios->removeElement($cofinancingScenario)) {
            // set the owning side to null (unless already changed)
            if ($cofinancingScenario->getUser() === $this) {
                $cofinancingScenario->setUser(null);
            }
        }

        return $this;
    }

    public function getCofinancingSum()
    {
        $arr = [
            'employersFunds' => 0,
            'OOFunds' => 0,
            'subjectFunds' => 0,
        ];
        foreach ($this->getCofinancingScenarios() as $scenario)
        {
            $arr['employersFunds'] += $scenario->getEmployersFunds();
            $arr['OOFunds'] += $scenario->getEducationFunds();
            $arr['subjectFunds'] += $scenario->getRegionFunds();
        }


        return $arr;
    }

}
