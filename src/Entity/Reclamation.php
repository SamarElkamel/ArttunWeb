<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReclamationRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]


class Reclamation
{
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\Column(name: "titre", type: "string", length: 50, nullable: false)]
     /**
 * @Assert\NotBlank(message="Le champ titre ne doit pas être vide.")
 * @Assert\Length(
 *      min = 3,
 *      minMessage = "Le champ doit contenir au moins 3 lettres."
 * )
 * @Assert\Regex(
 *     pattern="/^[a-zA-Z0-9\s]+$/",
 *     message="Le champ ne doit pas contenir de caractères spéciaux."
 * )
 */
    private $titre;

    #[ORM\Column(name: "description", type: "string", length: 100, nullable: false)]
     /**
 * @Assert\NotBlank(message="Le champ description ne doit pas être vide.")
 * @Assert\Length(
 *      min = 5,
 *      minMessage = "Le champ doit contenir au moins 5 lettres."
 * )
 
 */
    private $description;

   

    #[ORM\Column(name: "reply", type: "string", length: 255, nullable: true)]
    private $reply;

    #[ORM\Column(name: "id_client", type: "integer", nullable: true)]
    private $idClient;
    
    #[ORM\Column(name: "id_type", type: "integer", nullable: true)]
    private $idType;

    #[ORM\Column(name: "id_commande", type: "integer", nullable: true)]
    private $idCommande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

   

    public function getReply(): ?string
    {
        return $this->reply;
    }

    public function setReply(?string $reply): self
    {
        $this->reply = $reply;

        return $this;
    }

    public function getIdClient(): ?int
    {
        return $this->idClient;
    }

    public function setIdClient(?int $idClient): self
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getIdType(): ?int
    {
        return $this->idType;
    }

    public function setIdType(?int $idType): self
    {
        $this->idType = $idType;

        return $this;
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function setIdCommande(?int $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }
}
