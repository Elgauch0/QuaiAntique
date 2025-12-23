<?php

namespace App\Entity;

use App\Repository\RestaurantSettingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantSettingsRepository::class)]
class RestaurantSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $lunchStart = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $dinnerStart = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $maxConvives = null;

    public function __construct()
    {
        $this->lunchStart = new \DateTimeImmutable('12:00');
        $this->dinnerStart = new \DateTimeImmutable('19:00');
        $this->maxConvives = 30;
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLunchStart(): ?\DateTimeImmutable
    {
        return $this->lunchStart;
    }

    public function setLunchStart(\DateTimeImmutable $lunchStart): static
    {
        $this->lunchStart = $lunchStart;

        return $this;
    }

    public function getDinnerStart(): ?\DateTimeImmutable
    {
        return $this->dinnerStart;
    }

    public function setDinnerStart(\DateTimeImmutable $dinnerStart): static
    {
        $this->dinnerStart = $dinnerStart;

        return $this;
    }



    public function getLunchEnd(): ?\DateTimeImmutable
    {
        if ($this->lunchStart === null) {
            return null;
        }

        return $this->lunchStart->modify('+2 hours');
    }
    public function getDinnerEnd(): ?\DateTimeImmutable
    {
        if ($this->dinnerStart === null) {
            return null;
        }

        return $this->dinnerStart->modify('+2 hours');
    }

    public function getMaxConvives(): ?int
    {
        return $this->maxConvives;
    }

    public function setMaxConvives(int $maxConvives): static
    {
        $this->maxConvives = $maxConvives;

        return $this;
    }
}
