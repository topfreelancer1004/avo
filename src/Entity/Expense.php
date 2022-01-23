<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExpenseRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Expense
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Charge::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $charge_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $val;

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

    public function getChargeType(): ?Charge
    {
        return $this->charge_type;
    }

    public function setChargeType(?Charge $charge_type): self
    {
        $this->charge_type = $charge_type;

        return $this;
    }

    public function getVal(): ?int
    {
        return $this->val;
    }

    public function setVal(int $val): self
    {
        $this->val = $val;

        return $this;
    }

    /**
     * @return $this
     *
     * @ORM\PrePersist
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
        $this->created_at = $created_at;

        return $this;
    }
}
