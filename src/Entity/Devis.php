<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DevisRepository::class)
 */
class Devis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Procedure::class)
     */
    private $procedur;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="devis")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Aj::class)
     */
    private $aj;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paid;

    /**
     * @ORM\OneToMany(targetEntity=Paiment::class, mappedBy="devi")
     */
    private $paiments;

    public function __construct()
    {
        $this->paiments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

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


    public function getProcedur(): ?Procedure
    {
        return $this->procedur;
    }

    public function setProcedur(?Procedure $procedur): self
    {
        $this->procedur = $procedur;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAj(): ?Aj
    {
        return $this->aj;
    }

    public function setAj(?Aj $aj): self
    {
        $this->aj = $aj;

        return $this;
    }

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * @return Collection|Paiment[]
     */
    public function getPaiments(): Collection
    {
        return $this->paiments;
    }

    public function addPaiment(Paiment $paiment): self
    {
        if (!$this->paiments->contains($paiment)) {
            $this->paiments[] = $paiment;
            $paiment->setDevi($this);
        }

        return $this;
    }

    public function removePaiment(Paiment $paiment): self
    {
        if ($this->paiments->removeElement($paiment)) {
            // set the owning side to null (unless already changed)
            if ($paiment->getDevi() === $this) {
                $paiment->setDevi(null);
            }
        }

        return $this;
    }

    public function getPaimentsSum()
    {
        $payments = $this->getPaiments();
        $total = 0;
        foreach ($payments as $payment) {
            $total+=$payment->getAmount();
        }
        return $total;
    }

    public function getClientName (?Client $client): string
    {
        return $client;
    }

    public function __toString()
    {
        return $this->getCreatedAt()->format('Y-m-d');
    }
}
