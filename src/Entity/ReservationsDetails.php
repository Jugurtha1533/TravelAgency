<?php

namespace App\Entity;

use App\Repository\ReservationsDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsDetailsRepository::class)]
class ReservationsDetails
{
    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\Column(type: 'integer')]
    private $price;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Reservations::class, inversedBy: 'ReservationsDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private $Reservations;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Circuits::class, inversedBy: 'ReservationsDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private $circuits;

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getReservations(): ?Reservations
    {
        return $this->Reservations;
    }

    public function setReservations(?Reservations $Reservations): self
    {
        $this->Reservations = $Reservations;

        return $this;
    }

    public function getcircuits(): ?Circuits
    {
        return $this->circuits;
    }

    public function setcircuits(?Circuits $circuits): self
    {
        $this->circuits = $circuits;

        return $this;
    }
}
