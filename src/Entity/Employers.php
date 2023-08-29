<?php

namespace App\Entity;

use App\Repository\EmployersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployersRepository::class)
 */
class Employers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=UserInfo::class, mappedBy="employers")
     */
    private $userInfos;

    /**
     * @ORM\ManyToMany(targetEntity=EmployersCategory::class, mappedBy="employers")
     */
    private $employersCategories;

    /**
     * @ORM\OneToMany(targetEntity=EmployersContact::class, mappedBy="employer")
     */
    private $employersContacts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $inn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $altName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shortName;

    public function __construct()
    {
        $this->userInfos = new ArrayCollection();
        $this->employersCategories = new ArrayCollection();
        $this->employersContacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, UserInfo>
     */
    public function getUserInfos(): Collection
    {
        return $this->userInfos;
    }

    public function addUserInfo(UserInfo $userInfo): self
    {
        if (!$this->userInfos->contains($userInfo)) {
            $this->userInfos[] = $userInfo;
            $userInfo->addEmployer($this);
        }

        return $this;
    }

    public function removeUserInfo(UserInfo $userInfo): self
    {
        if ($this->userInfos->removeElement($userInfo)) {
            $userInfo->removeEmployer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, EmployersCategory>
     */
    public function getEmployersCategories(): Collection
    {
        return $this->employersCategories;
    }

    public function addEmployersCategory(EmployersCategory $employersCategory): self
    {
        if (!$this->employersCategories->contains($employersCategory)) {
            $this->employersCategories[] = $employersCategory;
            $employersCategory->addEmployer($this);
        }

        return $this;
    }

    public function removeEmployersCategory(EmployersCategory $employersCategory): self
    {
        if ($this->employersCategories->removeElement($employersCategory)) {
            $employersCategory->removeEmployer($this);
        }

        return $this;
    }

    public function getAsRow()
    {
        $row = [
            $this->getName(),
            $this->getAltName(),
            $this->getShortName(),
            $this->getDescription(),
            $this->arrayEmployersToStringList($this->getEmployersCategories()),
            $this->arrayUserInfosToStringList($this->getUserInfos()),
            $this->arrayYears($this->getUserInfos()),
            $this->arrayIndustry($this->getUserInfos()),
            $this->getInn(),
            $this->getCity(),

        ];
        return $row;

    }
    private function arrayEmployersToStringList($arr)
    {
        if(is_null($arr))
            return '';
        $str = "";
        $index = 1;
        foreach ($arr as $a)
        {
            $str = $str."$index) ".$a->getName()."\n";
            $index++;
        }
        return $str;
    }
    private function arrayUserInfosToStringList($arr)
    {
        if(is_null($arr))
            return '';
        $str = "";
        $index = 1;
        foreach ($arr as $a)
        {
            $str = $str."$index) ".$a->getEducationalOrganization()."\n";
            $index++;
        }
        return $str;
    }

    private function arrayYears($arr){
        if(is_null($arr))
            return '';
        $str = "";
        $index = 1;
        $years = [];
        foreach ($arr as $a)
        {
            array_push($years, $a->getYear());

        }
        $years = array_unique($years);
        foreach ($years as $a)
        {
            $str = $str."$index) ".$a."\n";
            $index++;
        }
        return $str;
    }
    private function arrayIndustry($arr){
        if(is_null($arr))
            return '';
        $str = "";
        $index = 1;
        $years = [];
        foreach ($arr as $a)
        {
            array_push($years, $a->getDeclaredIndustry());

        }
        $years = array_unique($years);
        foreach ($years as $a)
        {
            $str = $str.$a."\n";
            $index++;
        }
        return $str;
    }

    /**
     * @return Collection<int, EmployersContact>
     */
    public function getEmployersContacts(): Collection
    {
        return $this->employersContacts;
    }

    public function addEmployersContact(EmployersContact $employersContact): self
    {
        if (!$this->employersContacts->contains($employersContact)) {
            $this->employersContacts[] = $employersContact;
            $employersContact->setEmployer($this);
        }

        return $this;
    }

    public function removeEmployersContact(EmployersContact $employersContact): self
    {
        if ($this->employersContacts->removeElement($employersContact)) {
            // set the owning side to null (unless already changed)
            if ($employersContact->getEmployer() === $this) {
                $employersContact->setEmployer(null);
            }
        }

        return $this;
    }

    public function getInn(): ?string
    {
        return $this->inn;
    }

    public function setInn(?string $inn): self
    {
        $this->inn = $inn;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAltName(): ?string
    {
        return $this->altName;
    }

    public function setAltName(?string $altName): self
    {
        $this->altName = $altName;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }
}
