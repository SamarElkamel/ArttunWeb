<?php

namespace App\Entity;


use App\Repository\TypeReclamationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeReclamationRepository::class)]
class TypeReclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Libelle = null;

    #[ORM\Column(name: "NumUtilisateurs", type: "integer", nullable: true)]
    private ?int $NumUtilisateurs = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): static
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getNumUtilisateurs(): ?int
    {
        return $this->NumUtilisateurs;
    }

    public function setNumUtilisateurs(?int $NumUtilisateurs): static
    {
        $this->NumUtilisateurs = $NumUtilisateurs;

        return $this;
    }
}
