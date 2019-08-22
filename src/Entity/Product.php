<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="product", orphanRemoval=true, cascade={"remove"})
     * @Assert\Valid()
     */
    private $files;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOffLinePayed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="products")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OrphanUser", inversedBy="product", cascade={"persist", "remove"})
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

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Bill", mappedBy="product",orphanRemoval=true, cascade={"remove"})
     */
    private $bill;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Devis", mappedBy="product",orphanRemoval=true, cascade={"remove"})
     */
    private $devis;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentIntent;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isStripePayed;

    /**
     * @ORM\OneToMany(targetEntity="Livrable", mappedBy="product", orphanRemoval=true ,cascade={"remove"})
     */
    private $livrables;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->setIsOffLinePayed(false);
        $this->setIsStripePayed(false);
        $this->setState('En attente');
        $this->livrables = new ArrayCollection();
        $this->setCreationDate(new \DateTime());
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

    public function getIsOffLinePayed(): ?bool
    {
        return $this->isOffLinePayed;
    }

    public function setIsOffLinePayed(bool $isOffLinePayed): self
    {
        $this->isOffLinePayed = $isOffLinePayed;

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

    /**
     * @example En attente || Validée || Commencée || Terminée
     * @return string || null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): self
    {
        $this->bill = $bill;

        if(null===$bill) return $this;

        // set the owning side of the relation if necessary
        if ($this !== $bill->getProduct()) {
            $bill->setProduct($this);
        }

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;

        if(null === $devis) return $this;

        // set (or unset) the owning side of the relation if necessary
        $newProduct = $devis === null ? null : $this;
        if ($newProduct !== $devis->getProduct()) {
            $devis->setProduct($newProduct);
        }

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPaymentIntent(): ?string
    {
        return $this->paymentIntent;
    }

    public function setPaymentIntent(?string $paymentIntent): self
    {
        $this->paymentIntent = $paymentIntent;

        return $this;
    }

    public function getIsStripePayed(): ?bool
    {
        return $this->isStripePayed;
    }

    public function setIsStripePayed(bool $isStripePayed): self
    {
        $this->isStripePayed = $isStripePayed;

        return $this;
    }

    /**
     * @return Collection|Livrable[]
     */
    public function getLivrables(): Collection
    {
        return $this->livrables;
    }

    public function addLivrable(Livrable $livrable): self
    {
        if (!$this->livrables->contains($livrable)) {
            $this->livrables[] = $livrable;
            $livrable->setProduct($this);
        }

        return $this;
    }

    public function removeLivrable(Livrable $livrable): self
    {
        if ($this->livrables->contains($livrable)) {
            $this->livrables->removeElement($livrable);
            // set the owning side to null (unless already changed)
            if ($livrable->getProduct() === $this) {
                $livrable->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function getIsPayable()
    {
        if($this->getState() === ('En attente')) {

             return false;
        }

        return true;
    }

    /**
     * @param Product $product
     * @param $oldState
     * @return bool
     */
    public function getIsFinished($oldState)
    {
        if($this->getState() === 'Terminée') {

            if ($oldState != 'Terminée') {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * @param string $oldState
     * @return bool
     */
    public function getIsValidated(string $oldState)
    {
        if($this->getState() != 'En attente') {

            if ($oldState === 'En attente') {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getIsPayed()
    {
        if ($this->getIsOffLinePayed() || $this->getIsStripePayed()) {

            return true;
        }

        return false;
    }

    public function getIsValid()
    {
        if ($this->getState() !== 'En attente') return true;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
