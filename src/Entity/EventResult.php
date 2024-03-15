<?php

namespace App\Entity;

use App\Repository\EventResultRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventResultRepository::class)
 */
class EventResult
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    private $result;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $files = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentUser;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentCurator;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=UsersEvents::class, inversedBy="eventResult")
     */
    private $usersEvents;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getFiles(): ?array
    {
        return $this->files;
    }

    public function setFiles(?array $files): self
    {
        $this->files = $files;

        return $this;
    }

    public function getCommentUser(): ?string
    {
        return $this->commentUser;
    }

    public function setCommentUser(?string $commentUser): self
    {
        $this->commentUser = $commentUser;

        return $this;
    }

    public function getCommentCurator(): ?string
    {
        return $this->commentCurator;
    }

    public function setCommentCurator(?string $commentCurator): self
    {
        $this->commentCurator = $commentCurator;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUsersEvents(): ?UsersEvents
    {
        return $this->usersEvents;
    }

    public function setUsersEvents(?UsersEvents $usersEvents): self
    {
        $this->usersEvents = $usersEvents;

        return $this;
    }
}
