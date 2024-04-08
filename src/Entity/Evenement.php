<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenementRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]

class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;
    
    #[Assert\NotBlank(message: "Please enter the event name.")]
    #[ORM\Column(name: "Libelle", type: "string", length: 50, nullable: false)]
    private string $libelle;

    #[Assert\NotBlank(message: "Please enter a description .")]
    #[ORM\Column(name: "description", type: "string", length: 1000, nullable: false)]
    private string $description;

    #[Assert\GreaterThanOrEqual("today", message: "The date should not be in the past.")]
    #[ORM\Column(name: "date", type: "date", nullable: false)]
    private \DateTime $date;

    #[Assert\NotBlank(message: "Please enter the event costs .")]
    #[Assert\Type(type: "float", message: "Costs must be a numerical value.")]
    #[ORM\Column(name: "frais", type: "float", precision: 10, scale: 0, nullable: false)]
    private float $frais;

    #[ORM\Column(name: "photo", type: "string", length: 1000, nullable: false)]
    private string $photo;

    #[Assert\NotBlank(message: "Please enter the event localisation.")]
    #[ORM\Column(name: "localisation", type: "string", length: 100, nullable: false)]
    private string $localisation;

    #[Assert\NotBlank(message: "Please enter the event website.")]
    #[ORM\Column(name: "siteweb", type: "string", length: 1000, nullable: false)]
    private string $siteweb;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getFrais(): ?float
    {
        return $this->frais;
    }

    public function setFrais(float $frais): self
    {
        $this->frais = $frais;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getSiteweb(): ?string
    {
        return $this->siteweb;
    }

    public function setSiteweb(string $siteweb): self
    {
        $this->siteweb = $siteweb;
        return $this;
    }
}