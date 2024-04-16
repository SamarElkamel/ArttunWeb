<?php

namespace App\Entity;

use App\Repository\CategorieProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieProduitRepository::class)]
class CategorieProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(name: "libelle", type: "string", length: 50, nullable: false)]
    #[Assert\NotBlank(message: "Le libellé ne doit pas être vide.")]
    #[Assert\Length(max: 50, maxMessage: "Le libellé ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ\s]*$/',
        message: "Le libellé doit être une chaîne de caractères."
    )]
    private string $libelle;

    #[ORM\OneToMany(mappedBy: 'categorieProduit', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCategorieProduit($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            if ($produit->getCategorieProduit() === $this) {
                $produit->setCategorieProduit(null);
            }
        }

        return $this;
    }
}
