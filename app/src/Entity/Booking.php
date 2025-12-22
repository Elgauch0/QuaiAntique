<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $datetime = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $guest_count = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $allergies = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeImmutable
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeImmutable $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getGuestCount(): ?int
    {
        return $this->guest_count;
    }

    public function setGuestCount(int $guest_count): static
    {
        $this->guest_count = $guest_count;

        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): static
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getClient(): ?user
    {
        return $this->client;
    }

    public function setClient(?user $client): static
    {
        $this->client = $client;

        return $this;
    }
}
