<?php

namespace App\Entity\user;

use App\Repository\user\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @method string getUserIdentifier()
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface,TwoFactorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[Assert\NotBlank(message: "Please enter the user's first name.")]
    #[Assert\Length(max: 50, maxMessage: "First name cannot be longer than {{ limit }} characters.")]
    #[ORM\Column(name: "nom", type: "string", length: 50, nullable: false)]
    private string $nom;

    #[Assert\NotBlank(message: "Please enter the user's last name.")]
    #[Assert\Length(max: 50, maxMessage: "Last name cannot be longer than {{ limit }} characters.")]
    #[ORM\Column(name: "prenom", type: "string", length: 50, nullable: false)]
    private string $prenom;
    private PasswordHasherInterface $hasher;
    #[Assert\NotBlank(message: "Please enter the user's type.")]
    #[Assert\Length(max: 50, maxMessage: "Type cannot be longer than {{ limit }} characters.")]
    #[ORM\Column(name: "type", type: "string", length: 50, nullable: false)]
    private string $type;

    #[ORM\Column(name: "photo", type: "string", length: 100, nullable: true)]
    private ?string $photo;

    #[ORM\Column(name: "adresse", type: "integer", nullable: false)]
    private int $adresse;

    #[Assert\NotBlank(message: "Please enter the user's email address.")]
    #[Assert\Email(message: "Please enter a valid email address.")]
    #[Assert\Length(max: 100, maxMessage: "Email cannot be longer than {{ limit }} characters.")]
    #[ORM\Column(name: "adresse_mail", type: "string", length: 100, nullable: false)]
    private string $adresseMail;

    #[Assert\NotBlank(message: "Please enter the user's password.")]
    #[Assert\Length(min: 8, minMessage: "Password must be at least {{ limit }} characters long.")]
    #[ORM\Column(name: "mdp", type: "string", length: 300, nullable: false)]
    private string $mdp;

    public string $AuthCode ="";

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

    public function setId(int $id): self
    {
        $this->id = $id;
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
        $this->mdp =$mdp;
        return $this;
    }

    public function __toString(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }

    // Implementing UserInterface methods
    public function getRoles(): array
    {
        // Return an array of user roles, for example:
        // return ['ROLE_USER'];
        if($this->type=="admin")
        return ["ROLE_ADMIN"];
        else if($this->type=="client")
            return ["ROLE_CLIENT"];
        else
            return ["ROLE_LIVREUR"];
    }

    public function getPassword(): ?string
    {
        return $this->mdp;
    }

    public function getSalt(): ?string
    {
        // Return salt (if you use bcrypt or other modern password hashing methods, you don't need this)
        return null;
    }

    public function getUsername(): ?string
    {
        // Return the unique identifier for the user
        // For example, return email if it's unique
        return $this->adresseMail;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function isEmailAuthEnabled(): bool
    {
        return true;
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->adresseMail;
    }

    public function getEmailAuthCode(): ?string
    {
        return $this->AuthCode; // Using square brackets to access session variable
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->AuthCode=$authCode;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    private ?string $googleAuthenticatorSecret;

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return null !== $this->googleAuthenticatorSecret;
    }

   /* public function getGoogleAuthenticatorUsername(): string
    {
        return $this->username;
    }*/

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }
}
