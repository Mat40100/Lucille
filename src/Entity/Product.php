<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private $files;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPayed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="products")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OrphanUser", cascade={"persist", "remove"})
     */
    private $orphanUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $state;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->setIsValid(false);
        $this->setIsPayed(false);
        $this->setState('pending');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setProduct($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getProduct() === $this) {
                $file->setProduct(null);
            }
        }

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getIsPayed(): ?bool
    {
        return $this->isPayed;
    }

    public function setIsPayed(bool $isPayed): self
    {
        $this->isPayed = $isPayed;

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

    public function getOrphanUser(): ?OrphanUser
    {
        return $this->orphanUser;
    }

    public function setOrphanUser(?OrphanUser $orphanUser): self
    {
        $this->orphanUser = $orphanUser;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }
}
