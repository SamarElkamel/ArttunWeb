<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class User
{
    private ?int $id;

    private ?string $nom;

    private ?string $prenom;

    private ?string $type;

    private ?string $photo;

    private ?int $adresse;

    private ?string $adresseMail;

    private ?string $mdp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @ORM\Column(name="photo", type="string", length=100, nullable=false)
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @ORM\Column(name="adresse", type="integer", nullable=false)
     */
    public function getAdresse(): ?int
    {
        return $this->adresse;
    }

    public function setAdresse(int $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @ORM\Column(name="adresse_mail", type="string", length=100, nullable=false)
     */
    public function getAdresseMail(): ?string
    {
        return $this->adresseMail;
    }

    public function setAdresseMail(string $adresseMail): self
    {
        $this->adresseMail = $adresseMail;
        return $this;
    }

    /**
     * @ORM\Column(name="mdp", type="string", length=300, nullable=false)
     */
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
