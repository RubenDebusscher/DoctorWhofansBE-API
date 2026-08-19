<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 */
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'users_username_idx', columns: ['username'])]
#[ORM\Entity]
class Users
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'username', type: 'string', length: 191, nullable: false)]
    private $username;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'password', type: 'string', length: 255, nullable: true)]
    private $password;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'is_ldap_user', type: 'boolean', nullable: true)]
    private $isLdapUser = '0';

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: true)]
    private $name;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'email', type: 'string', length: 255, nullable: true)]
    private $email;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'google_id', type: 'string', length: 30, nullable: true)]
    private $googleId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'github_id', type: 'string', length: 30, nullable: true)]
    private $githubId;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'notifications_enabled', type: 'boolean', nullable: true)]
    private $notificationsEnabled = '0';

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'timezone', type: 'string', length: 50, nullable: true)]
    private $timezone;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'language', type: 'string', length: 11, nullable: true)]
    private $language;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'disable_login_form', type: 'boolean', nullable: true)]
    private $disableLoginForm = '0';

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'twofactor_activated', type: 'boolean', nullable: true)]
    private $twofactorActivated = '0';

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'twofactor_secret', type: 'string', length: 16, nullable: true, options: ['fixed' => true])]
    private $twofactorSecret;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'token', type: 'string', length: 255, nullable: true)]
    private $token = '';

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'notifications_filter', type: 'integer', nullable: true, options: ['default' => '4'])]
    private $notificationsFilter = 4;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'nb_failed_login', type: 'integer', nullable: true)]
    private $nbFailedLogin = '0';

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'lock_expiration_date', type: 'bigint', nullable: true)]
    private $lockExpirationDate;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'gitlab_id', type: 'integer', nullable: true)]
    private $gitlabId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'role', type: 'string', length: 25, nullable: false, options: ['default' => 'app-user'])]
    private $role = 'app-user';

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'is_active', type: 'boolean', nullable: true, options: ['default' => '1'])]
    private $isActive = true;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'avatar_path', type: 'string', length: 255, nullable: true)]
    private $avatarPath;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'api_access_token', type: 'string', length: 255, nullable: true)]
    private $apiAccessToken;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'filter', type: 'text', length: 16777215, nullable: true)]
    private $filter;

    /**
     * @var string
     */
    #[ORM\Column(name: 'theme', type: 'string', length: 50, nullable: false, options: ['default' => 'light'])]
    private $theme = 'light';

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'weight', type: 'integer', nullable: true)]
    private $weight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isLdapUser(): ?bool
    {
        return $this->isLdapUser;
    }

    public function setIsLdapUser(?bool $isLdapUser): static
    {
        $this->isLdapUser = $isLdapUser;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): static
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getGithubId(): ?string
    {
        return $this->githubId;
    }

    public function setGithubId(?string $githubId): static
    {
        $this->githubId = $githubId;

        return $this;
    }

    public function isNotificationsEnabled(): ?bool
    {
        return $this->notificationsEnabled;
    }

    public function setNotificationsEnabled(?bool $notificationsEnabled): static
    {
        $this->notificationsEnabled = $notificationsEnabled;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function isDisableLoginForm(): ?bool
    {
        return $this->disableLoginForm;
    }

    public function setDisableLoginForm(?bool $disableLoginForm): static
    {
        $this->disableLoginForm = $disableLoginForm;

        return $this;
    }

    public function isTwofactorActivated(): ?bool
    {
        return $this->twofactorActivated;
    }

    public function setTwofactorActivated(?bool $twofactorActivated): static
    {
        $this->twofactorActivated = $twofactorActivated;

        return $this;
    }

    public function getTwofactorSecret(): ?string
    {
        return $this->twofactorSecret;
    }

    public function setTwofactorSecret(?string $twofactorSecret): static
    {
        $this->twofactorSecret = $twofactorSecret;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getNotificationsFilter(): ?int
    {
        return $this->notificationsFilter;
    }

    public function setNotificationsFilter(?int $notificationsFilter): static
    {
        $this->notificationsFilter = $notificationsFilter;

        return $this;
    }

    public function getNbFailedLogin(): ?int
    {
        return $this->nbFailedLogin;
    }

    public function setNbFailedLogin(?int $nbFailedLogin): static
    {
        $this->nbFailedLogin = $nbFailedLogin;

        return $this;
    }

    public function getLockExpirationDate(): ?string
    {
        return $this->lockExpirationDate;
    }

    public function setLockExpirationDate(?string $lockExpirationDate): static
    {
        $this->lockExpirationDate = $lockExpirationDate;

        return $this;
    }

    public function getGitlabId(): ?int
    {
        return $this->gitlabId;
    }

    public function setGitlabId(?int $gitlabId): static
    {
        $this->gitlabId = $gitlabId;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }

    public function setAvatarPath(?string $avatarPath): static
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }

    public function getApiAccessToken(): ?string
    {
        return $this->apiAccessToken;
    }

    public function setApiAccessToken(?string $apiAccessToken): static
    {
        $this->apiAccessToken = $apiAccessToken;

        return $this;
    }

    public function getFilter(): ?string
    {
        return $this->filter;
    }

    public function setFilter(?string $filter): static
    {
        $this->filter = $filter;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }


}
