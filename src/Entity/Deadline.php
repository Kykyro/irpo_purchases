<?php

namespace App\Entity;

use App\Repository\DeadlineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeadlineRepository::class)
 */
class Deadline
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_of_publication;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $deadline_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $summing_up;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfPublication(): ?\DateTimeInterface
    {
        return $this->date_of_publication;
    }

    public function setDateOfPublication(?\DateTimeInterface $date_of_publication): self
    {
        $this->date_of_publication = $date_of_publication;

        return $this;
    }

    public function getDeadlineDate(): ?\DateTimeInterface
    {
        return $this->deadline_date;
    }

    public function setDeadlineDate(?\DateTimeInterface $deadline_date): self
    {
        $this->deadline_date = $deadline_date;

        return $this;
    }

    public function getSummingUp(): ?\DateTimeInterface
    {
        return $this->summing_up;
    }

    public function setSummingUp(?\DateTimeInterface $summing_up): self
    {
        $this->summing_up = $summing_up;

        return $this;
    }
}
