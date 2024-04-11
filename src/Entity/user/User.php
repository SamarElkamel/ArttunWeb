<?php

namespace App\Entity\user;

use App\Repository\user\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[Assert\NotBlank(message: "Please enter the user's first name.")]
    #[ORM\Column(name: "nom", type: "string", length: 50, nullable: false)]
    private string $nom;

    #[Assert\NotBlank(message: "Please enter the user's last name.")]
    #[ORM\Column(name: "prenom", type: "string", length: 50, nullable: false)]
    private string $prenom;

    #[Assert\NotBlank(message: "Please enter the user's type.")]
    #[ORM\Column(name: "type", type: "string", length: 50, nullable: false)]
    private string $type;

    #[ORM\Column(name: "photo", type: "string", length: 100, nullable: true)]
    private ?string $photo;

    #[Assert\NotBlank(message: "Please enter the user's address.")]
    #[ORM\Column(name: "adresse", type: "integer", nullable: false)]
    private int $adresse;

    #[Assert\NotBlank(message: "Please enter the user's email address.")]
    #[Assert\Email(message: "Please enter a valid email address.")]
    #[ORM\Column(name: "adresse_mail", type: "string", length: 100, nullable: false)]
    private string $adresseMail;

    #[Assert\NotBlank(message: "Please enter the user's password.")]
    #[ORM\Column(name: "mdp", type: "string", length: 300, nullable: false)]
    private string $mdp;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
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

    public function getAdresse(): ?int
    {
        return $this->adresse;
    }

    public function setAdresse(int $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getAdresseMail(): ?string
    {
        return $this->adresseMail;
    }

    public function setAdresseMail(string $adresseMail): self
    {
        $this->adresseMail = $adresseMail;
        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;
        return $this;
    }
}
