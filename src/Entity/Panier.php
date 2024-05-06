<?php
namespace App\Entity;

use App\Entity\user\User;
use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(name: "produit_id", referencedColumnName: "ref",nullable: false)]
    private ?Produit $ref_produit = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id",nullable: false)]
    private ?User $id_client = null;

 
  


    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefProduit(): ?Produit
    {
        return $this->ref_produit;
    }

    public function setRefProduit(?Produit $ref_produit): self
    {
        $this->ref_produit = $ref_produit;

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



}
