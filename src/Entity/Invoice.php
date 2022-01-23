<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Procedure::class)
     */
    private $procedur;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
    /**
     * @return $this
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function presetCreatedAt(): self
    {
        $this->created_at = new \DateTime();
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        if (!$created_at) {
            $created_at = \DateTime::createFromFormat('Y-m-d H:i:s', 'now');
        }
        $this->created_at = $created_at;

        return $this;
    }
}
