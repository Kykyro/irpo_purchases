<?php

namespace App\Entity;

use App\Repository\MonitoringCheckOutRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MonitoringCheckOutRepository::class)
 */
class MonitoringCheckOut
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
    private $file;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=UserInfo::class, inversedBy="monitoringCheckOuts")
     */
    private $userInfo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
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
}
