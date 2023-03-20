<?php

namespace App\Entity;

use App\Repository\ZoneRepairRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneRepairRepository::class)
 */
class ZoneRepair
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=ClusterZone::class, inversedBy="zoneRepair", cascade={"persist", "remove"})
     */
    private $clusterZone;


    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="integer")
     */
    private $Dismantling;

    /**
     * @ORM\Column(type="integer")
     */
    private $plasteringAndCommunication;

    /**
     * @ORM\Column(type="integer")
     */
    private $finishing;

    /**
     * @ORM\Column(type="integer")
     */
    private $branding;

    private $percentage = [
        'dismantling' => .1,
        'plasteringAndCommunication' => .3,
        'finishing' => .5,
        'branding' => .1
    ];

    /**
     * @ORM\OneToMany(targetEntity=PhotosVersion::class, mappedBy="repair")
     */
    private $photosVersions;

    public function __construct()
    {
        $this->photosVersions = new ArrayCollection();
        $this->setDismantling(0);
        $this->setPlasteringAndCommunication(0);
        $this->setFinishing(0);
        $this->setBranding(0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClusterZone(): ?ClusterZone
    {
        return $this->clusterZone;
    }

    public function setClusterZone(?ClusterZone $clusterZone): self
    {
        $this->clusterZone = $clusterZone;

        return $this;
    }



    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDismantling(): ?int
    {
        return $this->Dismantling;
    }

    public function setDismantling(?int $Dismantling): self
    {
        $this->Dismantling = $Dismantling;

        return $this;
    }

    public function getPlasteringAndCommunication(): ?int
    {
        return $this->plasteringAndCommunication;
    }

    public function setPlasteringAndCommunication(int $plasteringAndCommunication): self
    {
        $this->plasteringAndCommunication = $plasteringAndCommunication;

        return $this;
    }

    public function getFinishing(): ?int
    {
        return $this->finishing;
    }

    public function setFinishing(int $finishing): self
    {
        $this->finishing = $finishing;

        return $this;
    }

    public function getBranding(): ?int
    {
        return $this->branding;
    }

    public function setBranding(int $branding): self
    {
        $this->branding = $branding;

        return $this;
    }


    public function getTotalPercentage()
    {
        return
            $this->getDismaltingPercentage() +
            $this->getPlasteringAndCommunicationPercentage() +
            $this->getFinishingPercentage() +
            $this->getBrandingPercentage();
    }

    public function getDismaltingPercentage(){
        return $this->getDismantling() * $this->percentage['dismantling'];
    }
    public function getPlasteringAndCommunicationPercentage(){
        return $this->getPlasteringAndCommunication() * $this->percentage['plasteringAndCommunication'];
    }
    public function getFinishingPercentage(){
        return $this->getFinishing() * $this->percentage['finishing'];
    }
    public function getBrandingPercentage(){
        return $this->getBranding() * $this->percentage['branding'];
    }

    /**
     * @return Collection<int, PhotosVersion>
     */
    public function getPhotosVersions(): Collection
    {
        return $this->photosVersions;
    }

    public function addPhotosVersion(PhotosVersion $photosVersion): self
    {
        if (!$this->photosVersions->contains($photosVersion)) {
            $this->photosVersions[] = $photosVersion;
            $photosVersion->setRepair($this);
        }

        return $this;
    }

    public function removePhotosVersion(PhotosVersion $photosVersion): self
    {
        if ($this->photosVersions->removeElement($photosVersion)) {
            // set the owning side to null (unless already changed)
            if ($photosVersion->getRepair() === $this) {
                $photosVersion->setRepair(null);
            }
        }

        return $this;
    }
}
