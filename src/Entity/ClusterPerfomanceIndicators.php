<?php

namespace App\Entity;

use App\Repository\ClusterPerfomanceIndicatorsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClusterPerfomanceIndicatorsRepository::class)
 */
class ClusterPerfomanceIndicators
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=UserInfo::class, inversedBy="clusterPerfomanceIndicators")
     */
    private $userInfo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $studentCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ProgramCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $TeacherCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $WorkerCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $StudentCountWithMentor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $JobSecurityCount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $AmountOfFunding;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $AmountOfExtraFunds;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserInfo(): ?UserInfo
    {
        return $this->userInfo;
    }

    public function setUserInfo(?UserInfo $userInfo): self
    {
        $this->userInfo = $userInfo;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getStudentCount(): ?int
    {
        return $this->studentCount;
    }

    public function setStudentCount(?int $studentCount): self
    {
        $this->studentCount = $studentCount;

        return $this;
    }

    public function getProgramCount(): ?int
    {
        return $this->ProgramCount;
    }

    public function setProgramCount(int $ProgramCount): self
    {
        $this->ProgramCount = $ProgramCount;

        return $this;
    }

    public function getTeacherCount(): ?int
    {
        return $this->TeacherCount;
    }

    public function setTeacherCount(?int $TeacherCount): self
    {
        $this->TeacherCount = $TeacherCount;

        return $this;
    }

    public function getWorkerCount(): ?int
    {
        return $this->WorkerCount;
    }

    public function setWorkerCount(?int $WorkerCount): self
    {
        $this->WorkerCount = $WorkerCount;

        return $this;
    }

    public function getStudentCountWithMentor(): ?int
    {
        return $this->StudentCountWithMentor;
    }

    public function setStudentCountWithMentor(?int $StudentCountWithMentor): self
    {
        $this->StudentCountWithMentor = $StudentCountWithMentor;

        return $this;
    }

    public function getJobSecurityCount(): ?int
    {
        return $this->JobSecurityCount;
    }

    public function setJobSecurityCount(?int $JobSecurityCount): self
    {
        $this->JobSecurityCount = $JobSecurityCount;

        return $this;
    }

    public function getAmountOfFunding(): ?string
    {
        return $this->AmountOfFunding;
    }

    public function setAmountOfFunding(?string $AmountOfFunding): self
    {
        $this->AmountOfFunding = $AmountOfFunding;

        return $this;
    }

    public function getAmountOfExtraFunds(): ?string
    {
        return $this->AmountOfExtraFunds;
    }

    public function setAmountOfExtraFunds(?string $AmountOfExtraFunds): self
    {
        $this->AmountOfExtraFunds = $AmountOfExtraFunds;

        return $this;
    }
}
