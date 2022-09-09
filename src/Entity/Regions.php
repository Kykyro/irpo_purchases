<?php

namespace App\Entity;

use App\Repository\RegionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegionsRepository::class)
 */
class Regions
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
    private $viewPort;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ident;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $polygons = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $paths = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $organization = [];


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setViewPort(?string $viewPort): self
    {
        $this->viewPort = $viewPort;

        return $this;
    }

    public function getIdent(): ?int
    {
        return $this->ident;
    }

    public function setIdent(?int $ident): self
    {
        $this->ident = $ident;

        return $this;
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

    public function getPolygons(): ?array
    {
        return $this->polygons;
    }

    public function setPolygons(?array $polygons): self
    {
        $this->polygons = $polygons;

        return $this;
    }

    public function getPaths(): ?array
    {
        return $this->paths;
    }

    public function setPaths(?array $paths): self
    {
        $this->paths = $paths;

        return $this;
    }

    public function getOrganization(): ?array
    {
        return $this->organization;
    }

    public function setOrganization(?array $organization): self
    {
        $this->organization = $organization;

        return $this;
    }


}
