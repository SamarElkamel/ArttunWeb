<?php

namespace App\Entity;
use App\Repository\CommandesRepository;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]

class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(name: "refernce", type: "string", length: 255, nullable: false)]
    private string $refernce;

    #[ORM\Column(name: "date", type: "date", nullable: false)]
    private \DateTime $date;

    #[ORM\Column(name: "id_produit", type: "integer", nullable: false)]
    private int $idProduit;

    #[ORM\Column(name: "id_client", type: "integer", nullable: true)]
    private ?int $idClient;

    #[ORM\Column(name: "id_mission", type: "integer", nullable: true)]
    private ?int $idMission;

    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: true)]
    private ?float $prix;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'id_mission', referencedColumnName: 'id_mission', nullable: true)]
    private ?Mission $mission = null;



    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): static
    {
        $this->mission = $mission;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefernce(): ?string
    {
        return $this->refernce;
    }

    public function setRefernce(string $refernce): self
    {
        $this->refernce = $refernce;
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

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function setIdProduit(int $idProduit): self
    {
        $this->idProduit = $idProduit;
        return $this;
    }

    public function getIdClient(): ?int
    {
        return $this->idClient;
    }

    public function setIdClient(?int $idClient): self
    {
        $this->idClient = $idClient;
        return $this;
    }

    public function getIdMission(): ?int
    {
        return $this->idMission;
    }

    public function setIdMission(?int $idMission): self
    {
        $this->idMission = $idMission;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }
}
