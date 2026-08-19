<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiSeasons
 */
#[ORM\Table(name: 'api__seasons')]
#[ORM\Index(name: 'API__seasons__Last__Modified__User__ID', columns: ['season_Last_modifier'])]
#[ORM\Index(name: 'show_id', columns: ['season_Show_Id'])]
#[ORM\Index(name: 'API__seasons__Owner__ID', columns: ['season_Owner_Id'])]
#[ORM\Entity]
class ApiSeasons
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'season_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $seasonId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'season_Name', type: 'text', length: 65535, nullable: false)]
    private $seasonName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'season_Order', type: 'decimal', precision: 19, scale: 4, nullable: false)]
    private $seasonOrder;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'season_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $seasonCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'season_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $seasonLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ApiShows
     */
    #[ORM\JoinColumn(name: 'season_Show_Id', referencedColumnName: 'show_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiShows::class)]
    private $seasonShow;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'season_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $seasonOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'season_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $seasonLastModifier;

    public function getSeasonId(): ?int
    {
        return $this->seasonId;
    }

    public function getSeasonName(): ?string
    {
        return $this->seasonName;
    }

    public function setSeasonName(string $seasonName): static
    {
        $this->seasonName = $seasonName;

        return $this;
    }

    public function getSeasonOrder(): ?string
    {
        return $this->seasonOrder;
    }

    public function setSeasonOrder(string $seasonOrder): static
    {
        $this->seasonOrder = $seasonOrder;

        return $this;
    }

    public function getSeasonCreatedAt(): ?\DateTime
    {
        return $this->seasonCreatedAt;
    }

    public function setSeasonCreatedAt(\DateTime $seasonCreatedAt): static
    {
        $this->seasonCreatedAt = $seasonCreatedAt;

        return $this;
    }

    public function getSeasonLastModifiedAt(): ?\DateTime
    {
        return $this->seasonLastModifiedAt;
    }

    public function setSeasonLastModifiedAt(\DateTime $seasonLastModifiedAt): static
    {
        $this->seasonLastModifiedAt = $seasonLastModifiedAt;

        return $this;
    }

    public function getSeasonShow(): ?ApiShows
    {
        return $this->seasonShow;
    }

    public function setSeasonShow(?ApiShows $seasonShow): static
    {
        $this->seasonShow = $seasonShow;

        return $this;
    }

    public function getSeasonOwner(): ?ManagementUsers
    {
        return $this->seasonOwner;
    }

    public function setSeasonOwner(?ManagementUsers $seasonOwner): static
    {
        $this->seasonOwner = $seasonOwner;

        return $this;
    }

    public function getSeasonLastModifier(): ?ManagementUsers
    {
        return $this->seasonLastModifier;
    }

    public function setSeasonLastModifier(?ManagementUsers $seasonLastModifier): static
    {
        $this->seasonLastModifier = $seasonLastModifier;

        return $this;
    }


}
