<?php

namespace App\Entity;

use App\Repository\UsersEventsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersEventsRepository::class)
 */
class UsersEvents
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
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $finishDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="usersEvents")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=EventResult::class, mappedBy="usersEvents")
     */
    private $eventResult;

    function __construct()
    {
        $this->setDeleted(false);
        $this->eventResult = new ArrayCollection();
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

    public function getFinishDate(): ?\DateTimeImmutable
    {
        return $this->finishDate;
    }

    public function setFinishDate(?\DateTimeImmutable $finishDate): self
    {
        $this->finishDate = $finishDate;

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

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, EventResult>
     */
    public function getEventResult(): Collection
    {
        return $this->eventResult;
    }
    public function getStatus()
    {
        if(count($this->eventResult) > 0)
        {
            $criteria = Criteria::create()
                ->orderBy(['id' => 'DESC'])
                ->setMaxResults(1)
            ;
//            dd($this->eventResult->matching($criteria));
            return $this->eventResult->matching($criteria)[0]->getStatus();
        }
        else{
            return 'Нет информации';
        }
    }

    public function addEventResult(EventResult $eventResult): self
    {
        if (!$this->eventResult->contains($eventResult)) {
            $this->eventResult[] = $eventResult;
            $eventResult->setUsersEvents($this);
        }

        return $this;
    }

    public function removeEventResult(EventResult $eventResult): self
    {
        if ($this->eventResult->removeElement($eventResult)) {
            // set the owning side to null (unless already changed)
            if ($eventResult->getUsersEvents() === $this) {
                $eventResult->setUsersEvents(null);
            }
        }

        return $this;
    }
}
