<?php

namespace App\Entity;
use App\Entity\Command;
use App\Repository\MissionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_mission", type: "integer", nullable: false)]
    private ?int $idMission;

    #[ORM\Column(name: "etat", type: "string", length: 50, nullable: false)]
    private string $etat;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[ORM\JoinColumn(name: 'id_livreur', referencedColumnName: 'id', nullable: true)]
    private ?Livreur $livreur = null;
    
    #[ORM\OneToMany(targetEntity: Command::class, mappedBy: 'mission')]
    private Collection $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getIdMission(): ?int
    {
        return $this->idMission;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?Livreur $livreur): self
    {
        $this->livreur = $livreur;
        return $this;
    }

    
    public function addCommande(Command $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setMission(null);
        }

        return $this;
    }

    
    public function removeCommande(Command $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // Définit le côté "mission" de la relation à null
            if ($commande->getMission() === $this) {
                $commande->setMission(null);
            }
        }

        return $this;
    }
}