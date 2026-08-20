<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

/**
 * ManagementUsers
 */
#[ORM\Table(name: 'management__users')]
#[ORM\Entity]
class ManagementUsers implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'user_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['user:read', 'content_type:read', 'attribute:read'])]
    private $userId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'user_Name', type: 'string', length: 50, nullable: false)]
    #[Groups(['user:read', 'content_type:read', 'attribute:read','v3_item:detail'])]
    private $userName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'user_Password', type: 'string', length: 255, nullable: false)]
    #[Ignore]
    private $userPassword;

    /**
     * @var string
     */
    #[ORM\Column(name: 'user_Email', type: 'string', length: 200, nullable: false)]
    #[Groups(['user:read'])]
    private $userEmail;

    /**
     * @var string
     */
    #[ORM\Column(name: 'user_Role', type: 'string', length: 50, nullable: false)]
    #[Groups(['user:read','v3_item:detail'])]
    private $userRole;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'user_Image', type: 'text', length: 0, nullable: true)]
    #[Groups(['user:read', 'content_type:read', 'attribute:read'])]
    private $userImage;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): static
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->userPassword;
    }

    public function setUserPassword(string $userPassword): static
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    public function setUserRole(string $userRole): static
    {
        $this->userRole = $userRole;

        return $this;
    }

    public function getUserImage(): ?string
    {
        return $this->userImage;
    }

    public function setUserImage(?string $userImage): static
    {
        $this->userImage = $userImage;

        return $this;
    }





    /*Authentication */

    /**
     * Vertaalt het Symfony wachtwoord-mechanisme naar jouw $userPassword property
     */
    public function getPassword(): ?string
    {
        return $this->userPassword;
    }

    /**
     * Zorgt dat Symfony de gebruikersnaam snapt voor authenticatie
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->userName;
    }

    /**
     * Bepaalt de Symfony rollen op basis van de $userRole kolom
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        // Als het veld userRole 'ADMIN' of 'MANAGER' bevat, geef dan admin rechten
        if (strtoupper((string)$this->userRole) === 'ADMIN') {
            $roles[] = 'ROLE_ADMIN';
        }

        return array_unique($roles);
    }
    #[\Deprecated(since: 'symfony/security-http 7.3')]
    public function eraseCredentials(): void
    {
        // Optioneel voor het wissen van tijdelijke gevoelige gegevens
    }
}
