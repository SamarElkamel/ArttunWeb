<?php
namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "ref", type: "integer", nullable: false)]
    private int $ref;

    #[ORM\Column(name: "nom", type: "string", length: 50, nullable: false)]
    private string $nom;

    #[ORM\Column(name: "image", type: "string", length: 50, nullable: false)]
    private string $image;

    #[ORM\Column(name: "prix", type: "integer", nullable: false)]
    private int $prix;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: false)]
    private string $description;

    #[ORM\Column(name: "id_categorie", type: "integer", nullable: false)]
    private int $id_categorie;

    #[ORM\OneToMany(mappedBy: 'produit_id', targetEntity: Panier::class)]
    private Collection $paniers;

    #[ORM\OneToMany(mappedBy: 'id_produit', targetEntity: Command::class)]
    private Collection $commands;
    #[ORM\ManyToMany(targetEntity: Produit::class)]
    #[ORM\JoinTable(name: "command_produit")]
private Collection $produits;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
        $this->commands = new ArrayCollection();
    }

    // Getter and Setter for 'ref'
    public function getRef(): ?int
    {
        return $this->ref;
    }

    // 'ref' is auto-generated, so no setter is needed

    // Getter and Setter for 'nom'
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    // Getter and Setter for 'image'
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    // Getter and Setter for 'prix'
    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    // Getter and Setter for 'description'
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    // Getter and Setter for 'id_categorie'
    public function getIdCategorie(): ?int
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(int $id_categorie): self
    {
        $this->id_categorie = $id_categorie;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->setRefProduit($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getRefProduit() === $this) {
                $panier->setRefProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Command>
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Command $command): static
    {
        if (!$this->commands->contains($command)) {
            $this->commands->add($command);
            $command->setIdProduit($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): static
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getIdProduit() === $this) {
                $command->setIdProduit(null);
            }
        }

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
        $this->produits[] = $produit;
    }

    return $this;
}

public function removeProduit(Produit $produit): self
{
    $this->produits->removeElement($produit);

    return $this;
}

}
