<?php

namespace App\Entity;

use App\Repository\RepairPhotosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RepairPhotosRepository::class)
 */
class RepairPhotos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PhotosVersion::class, inversedBy="repairPhotos")
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?PhotosVersion
    {
        return $this->version;
    }

    public function setVersion(?PhotosVersion $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
    public function allowToDelete($time){
        $saveTime = $this->getVersion()->getCreatedAt();
        $timeDiff = $saveTime->diff($time);

        if((int)$timeDiff->format("%a") < 1)
        {
            return true;
        }
        else{
            return false;
        }
    }
}
