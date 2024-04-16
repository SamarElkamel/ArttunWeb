<?php
namespace App\Entity;

use App\Repository\LivreurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: LivreurRepository::class)]
#[UniqueEntity(fields: ['mail'], message: 'Cet email est déjà utilisé. Veuillez en choisir un autre.')]

class Livreur
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id;

    #[ORM\Column(name: "nom", type: "string", length: 50, nullable: false)]
    #[Assert\Regex( pattern: '/^[^\W_0-9]+$/', message: 'Votre saisie ne doit pas contenir de caractères spéciaux ni de chiffres.')]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private ?string $nom;

    #[ORM\Column(name: "prenom", type: "string", length: 50, nullable: false)]
    #[Assert\Regex( pattern: '/^[^\W_0-9]+$/', message: 'Votre saisie ne doit pas contenir de caractères spéciaux ni de chiffres.')]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    private ?string $prenom;

    #[ORM\Column(name: "photo", type: "string", length: 500, nullable: false)]
    #[Assert\NotBlank(message: "La photo ne peut pas être vide.")]

    private ?string $photo;

    #[ORM\Column(name: "mail", type: "string", length: 100, nullable: false)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,4}$/',
        message: 'Veuillez saisir une adresse email valide de la forme "adresse@domaine.com".'
    )]
    #[Assert\NotBlank(message: "L'adresse email ne peut pas être vide.")]
    private ?string $mail;

    #[ORM\Column(name: "mdp", type: "string", length: 50, nullable: false)]
    #[Assert\Regex(
        pattern: '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\d\s])\S{8,}$/',
        message: 'Le mot de passe doit contenir au moins un caractère majuscule, un caractère minuscule, un chiffre et un caractère spécial, et avoir une longueur minimale de 8 caractères.'
    )]
    #[Assert\NotBlank(message: "Le mot de passe ne peut pas être vide.")]
    private ?string $mdp;

    #[ORM\OneToMany(targetEntity: Mission::class, mappedBy: 'livreur')]
    private Collection $missions;


    public function __toString(): string
    {
        return $this->getNom(); // Assuming there's a method getName() that returns the name of the Livreur
    }

    public function __construct()
    {
        $this->missions = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): static
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setLivreur($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getLivreur() === $this) {
                $mission->setLivreur(null);
            }
        }

        return $this;
    }
   
}
