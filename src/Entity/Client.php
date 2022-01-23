<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=8,
     *     max=15
     * )
     * @Assert\Regex(
     *     pattern="/^[0-9-]*$/",
     *     message="Numbers and '-' only"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $zip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="clients")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Paiment::class, mappedBy="client")
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(?string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param  \DateTime  $created_at
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $created_at): self
    {
        if (!$created_at) {
            $created_at = \DateTime::createFromFormat('Y-m-d H:i:s', 'now');
        }
        $this->created_at = $created_at;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function __toString():string
    {
        return $this->getFirstName().' '.$this->getLastName();
    }
}
