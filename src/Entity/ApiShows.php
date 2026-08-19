<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ApiShows
 */
#[ORM\Table(name: 'api__shows')]
#[ORM\Index(name: 'API__shows__Owner__ID', columns: ['show_Owner_Id'])]
#[ORM\Index(name: 'API__shows__Last__Modified__User__ID', columns: ['show_Last_modifier'])]
#[ORM\Entity]
class ApiShows
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'show_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $showId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'show_Name', type: 'string', length: 50, nullable: false)]
    private $showName;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'show_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $showCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'show_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $showLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'show_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $showLastModifier;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'show_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $showOwner;

    public function getShowId(): ?int
    {
        return $this->showId;
    }

    public function getShowName(): ?string
    {
        return $this->showName;
    }

    public function setShowName(string $showName): static
    {
        $this->showName = $showName;

        return $this;
    }

    public function getShowCreatedAt(): ?\DateTime
    {
        return $this->showCreatedAt;
    }

    public function setShowCreatedAt(\DateTime $showCreatedAt): static
    {
        $this->showCreatedAt = $showCreatedAt;

        return $this;
    }

    public function getShowLastModifiedAt(): ?\DateTime
    {
        return $this->showLastModifiedAt;
    }

    public function setShowLastModifiedAt(\DateTime $showLastModifiedAt): static
    {
        $this->showLastModifiedAt = $showLastModifiedAt;

        return $this;
    }

    public function getShowLastModifier(): ?ManagementUsers
    {
        return $this->showLastModifier;
    }

    public function setShowLastModifier(?ManagementUsers $showLastModifier): static
    {
        $this->showLastModifier = $showLastModifier;

        return $this;
    }

    public function getShowOwner(): ?ManagementUsers
    {
        return $this->showOwner;
    }

    public function setShowOwner(?ManagementUsers $showOwner): static
    {
        $this->showOwner = $showOwner;

        return $this;
    }


}
