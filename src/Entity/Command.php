<?php

namespace App\Entity;

use App\Entity\user\User;
use App\Entity\Mission;
use App\Repository\CommandRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(name: "reference", type: "string", length: 50, nullable: false)]
    private string $reference;

    #[ORM\Column(name: "date", type: "date", nullable: false)]
    private \DateTime $date;

    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: false)]
    private float $prix;

    #[ORM\ManyToOne(inversedBy: 'commands')]
    #[ORM\JoinColumn(name: "id_produit", referencedColumnName: "ref",nullable: false)]
    private ?Produit $id_produit = null;

    #[ORM\ManyToOne(inversedBy: 'commands')]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id",nullable: false)]
    private ?User $id_client = null;

    #[ORM\ManyToOne(inversedBy: 'commands')]
    #[ORM\JoinColumn(name: "id_mission", referencedColumnName: "id_mission",nullable: false)]
    private ?Mission $id_mission = null;

 


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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->id_produit;
    }

    public function setIdProduit(?Produit $id_produit): static
    {
        $this->id_produit = $id_produit;

        return $this;
    }

    public function getIdClient(): ?User
    {
        return $this->id_client;
    }

    public function setIdClient(?User $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getIdMission(): ?Mission
    {
        return $this->id_mission;
    }

    public function setIdMission(?Mission $id_mission): static
    {
        $this->id_mission = $id_mission;

        return $this;
    }

   

    

   
}
