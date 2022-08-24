<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
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
    private $object_class;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $foreign_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $action;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $old_val;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $new_val;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $field_name;

    function __construct(?string $objClass, ?int $f_key, ?string $act, ?string $old, ?string $new, ?string $field, ?int $version) {
        $this->setObjectClass($objClass);
        $this->setForeignKey($f_key);
        $this->setAction($act);
        $this->setOldVal($old);
        $this->setNewVal($new);
        $this->setFieldName($field);
        $this->setVersion($version);
        $this->setCreatedAt(new \DateTimeImmutable('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectClass(): ?string
    {
        return $this->object_class;
    }

    public function setObjectClass(?string $object_class): self
    {
        $this->object_class = $object_class;

        return $this;
    }

    public function getForeignKey(): ?int
    {
        return $this->foreign_key;
    }

    public function setForeignKey(?int $foreign_key): self
    {
        $this->foreign_key = $foreign_key;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getOldVal(): ?string
    {
        return $this->old_val;
    }

    public function setOldVal(?string $old_val): self
    {
        $this->old_val = $old_val;

        return $this;
    }

    public function getNewVal(): ?string
    {
        return $this->new_val;
    }

    public function setNewVal(?string $new_val): self
    {
        $this->new_val = $new_val;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getFieldName(): ?string
    {
        return $this->field_name;
    }

    public function setFieldName(?string $field_name): self
    {
        $this->field_name = $field_name;

        return $this;
    }
}
