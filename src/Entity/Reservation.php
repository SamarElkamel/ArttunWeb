<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'réservation')]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'date')]
    private \DateTime $date;

    #[ORM\Column(type: 'float', precision: 10, scale: 0)]
    private float $totalprix;

    #[ORM\Column(type: 'integer')]
    private int $nbinvite;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id')]
    private User $idClient;

    #[ORM\ManyToOne(targetEntity: Evenement::class)]
    #[ORM\JoinColumn(name: 'id_evenement', referencedColumnName: 'id')]
    private Evenement $idEvenement;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTotalPrix(): float
    {
        return $this->totalprix;
    }

    public function setTotalPrix(float $totalprix): self
    {
        $this->totalprix = $totalprix;

        return $this;
    }

    public function getNbinvite(): int
    {
        return $this->nbinvite;
    }

    public function setNbinvite(int $nbinvite): self
    {
        $this->nbinvite = $nbinvite;

        return $this;
    }

    public function getIdClient(): User
    {
        return $this->idClient;
    }

    public function setIdClient(User $idClient): self
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getIdEvenement(): Evenement
    {
        return $this->idEvenement;
    }

    public function setIdEvenement(Evenement $idEvenement): self
    {
        $this->idEvenement = $idEvenement;

        return $this;
    }
}
