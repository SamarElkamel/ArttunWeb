<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "ref", type: "integer", nullable: false)]
    private int $ref;

    #[ORM\Column(name: "nom", type: "string", length: 50, nullable: false)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(max: 50, maxMessage: "Le Nom ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ\s]*$/',
        message: "Le Nom doit être une chaîne de caractères."
    )]
    private string $nom;

    #[ORM\Column(name: "image", type: "string", length: 500, nullable: false)]
    private string $image;

    #[ORM\Column(name: "prix", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Le prix est obligatoire.")]
    
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: "La Prix doit être entier."
    )]
    private int $prix;

    #[ORM\Column(name: "description", type: "string", length: 100, nullable: false)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(max: 50, maxMessage: "Le Description ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ\s]*$/',
        message: "La Description doit être une chaîne de caractères."
    )]
    private string $description;

    #[ORM\ManyToOne(targetEntity: CategorieProduit::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(name: "id_categorie", referencedColumnName: "id", nullable: false)]
    private CategorieProduit $categorieProduit;

    public function __construct()
    {
    }

    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;
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

    public function getCategorieProduit(): ?CategorieProduit
    {
        return $this->categorieProduit;
    }

    public function setCategorieProduit(CategorieProduit $categorieProduit): self
    {
        $this->categorieProduit = $categorieProduit;
        return $this;
    }
}
