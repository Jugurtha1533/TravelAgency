<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Repository\ReservationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20, unique: true)]
    private $reference;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private $users;

    #[ORM\OneToMany(mappedBy: 'Reservations', targetEntity: ReservationsDetails::class, orphanRemoval: true, cascade: ["persist"])]
    private $ReservationsDetails;

    #[ORM\Column(nullable: true)]
    private ?float $total = null;

    public function __construct()
    {
        $this->ReservationsDetails = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCoupons(): ?Coupons
    {
        return $this->coupons;
    }

    public function setCoupons(?Coupons $coupons): self
    {
        $this->coupons = $coupons;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection|ReservationsDetails[]
     */
    public function getReservationsDetails(): Collection
    {
        return $this->ReservationsDetails;
    }

    public function addReservationsDetail(ReservationsDetails $ReservationsDetail): self
    {
        if (!$this->ReservationsDetails->contains($ReservationsDetail)) {
            $this->ReservationsDetails[] = $ReservationsDetail;
            $ReservationsDetail->setReservations($this);
        }

        return $this;
    }

    public function removeReservationsDetail(ReservationsDetails $ReservationsDetail): self
    {
        if ($this->ReservationsDetails->removeElement($ReservationsDetail)) {
            // set the owning side to null (unless already changed)
            if ($ReservationsDetail->getReservations() === $this) {
                $ReservationsDetail->setReservations(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
