<?php

namespace App\Entity\user;

use App\Repository\AdressesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdressesRepository::class)]
class Adresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[Assert\NotBlank(message: "Please enter the country.")]
    #[ORM\Column(name: "country", type: "string", length: 100, nullable: false)]
    private string $country;

    #[ORM\Column(name: "state", type: "string", length: 100, nullable: true)]
    private ?string $state;

    #[Assert\NotBlank(message: "Please enter the city.")]
    #[ORM\Column(name: "city", type: "string", length: 100, nullable: false)]
    private string $city;

    #[Assert\NotBlank(message: "Please enter the postal code.")]
    #[ORM\Column(name: "code", type: "integer", nullable: false)]
    private int $code;

    #[Assert\NotBlank(message: "Please enter the street address.")]
    #[ORM\Column(name: "street", type: "string", length: 255, nullable: false)]
    private string $street;

    #[ORM\Column(name: "lon", type: "float", nullable: false)]
    private float $lon;

    #[ORM\Column(name: "lat", type: "float", nullable: false)]
    private float $lat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
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

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getLon(): float
    {
        return $this->lon;
    }

    public function setLon(float $lon): self
    {
        $this->lon = $lon;
        return $this;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;
        return $this;
    }
}
