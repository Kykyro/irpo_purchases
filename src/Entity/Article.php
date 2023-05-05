<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articleFile;

    /**
     * @ORM\OneToMany(targetEntity=ArticleFiles::class, mappedBy="article")
     */
    private $articleFiles;



    public function __construct()
    {
        $this->name = new ArrayCollection();
        $this->articleFiles = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getArticleFile(): ?string
    {
        return $this->articleFile;
    }

    public function setArticleFile(?string $articleFile): self
    {
        $this->articleFile = $articleFile;

        return $this;
    }

    /**
     * @return Collection<int, ArticleFiles>
     */
    public function getArticleFiles(): Collection
    {
        return $this->articleFiles;
    }

    public function addArticleFile(ArticleFiles $articleFile): self
    {
        if (!$this->articleFiles->contains($articleFile)) {
            $this->articleFiles[] = $articleFile;
            $articleFile->setArticle($this);
        }

        return $this;
    }

    public function removeArticleFile(ArticleFiles $articleFile): self
    {
        if ($this->articleFiles->removeElement($articleFile)) {
            // set the owning side to null (unless already changed)
            if ($articleFile->getArticle() === $this) {
                $articleFile->setArticle(null);
            }
        }

        return $this;
    }
}
