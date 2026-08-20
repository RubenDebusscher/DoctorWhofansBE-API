<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiSerials
 */
#[ORM\Table(name: 'api__serials')]
#[ORM\Index(name: 'API__serials__Owner__ID', columns: ['serial_Owner_Id'])]
#[ORM\Index(name: 'API__serials__Last__Modified__User__ID', columns: ['serial_Last_modifier'])]
#[ORM\Index(name: 'season_id', columns: ['season_Id'])]
#[ORM\Entity]
class ApiSerials
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'serial_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $serialId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'serial_Story', type: 'text', length: 65535, nullable: true)]
    private $serialStory;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'serial_Order', type: 'integer', nullable: true, options: ['comment' => 'This is the order it takes within a season'])]
    private $serialOrder;

    /**
     * @var string
     */
    #[ORM\Column(name: 'serial_Title', type: 'text', length: 65535, nullable: false)]
    private $serialTitle;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'serial_Production_code', type: 'text', length: 65535, nullable: true)]
    private $serialProductionCode;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'serial_Image', type: 'string', length: 150, nullable: true)]
    private $serialImage;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'serial_Previous_Id', type: 'integer', nullable: true)]
    private $serialPreviousId;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'serial_Next_Id', type: 'integer', nullable: true)]
    private $serialNextId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'Page_Id', type: 'integer', nullable: false)]
    private $pageId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serial_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $serialCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serial_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $serialLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'serial_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $serialLastModifier;

    /**
     * @var \ApiSeasons
     */
    #[ORM\JoinColumn(name: 'season_Id', referencedColumnName: 'season_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiSeasons::class)]
    private $season;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'serial_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $serialOwner;

    public function getSerialId(): ?int
    {
        return $this->serialId;
    }

    public function getSerialStory(): ?string
    {
        return $this->serialStory;
    }

    public function setSerialStory(?string $serialStory): static
    {
        $this->serialStory = $serialStory;

        return $this;
    }

    public function getSerialOrder(): ?int
    {
        return $this->serialOrder;
    }

    public function setSerialOrder(?int $serialOrder): static
    {
        $this->serialOrder = $serialOrder;

        return $this;
    }

    public function getSerialTitle(): ?string
    {
        return $this->serialTitle;
    }

    public function setSerialTitle(string $serialTitle): static
    {
        $this->serialTitle = $serialTitle;

        return $this;
    }

    public function getSerialProductionCode(): ?string
    {
        return $this->serialProductionCode;
    }

    public function setSerialProductionCode(?string $serialProductionCode): static
    {
        $this->serialProductionCode = $serialProductionCode;

        return $this;
    }

    public function getSerialImage(): ?string
    {
        return $this->serialImage;
    }

    public function setSerialImage(?string $serialImage): static
    {
        $this->serialImage = $serialImage;

        return $this;
    }

    public function getSerialPreviousId(): ?int
    {
        return $this->serialPreviousId;
    }

    public function setSerialPreviousId(?int $serialPreviousId): static
    {
        $this->serialPreviousId = $serialPreviousId;

        return $this;
    }

    public function getSerialNextId(): ?int
    {
        return $this->serialNextId;
    }

    public function setSerialNextId(?int $serialNextId): static
    {
        $this->serialNextId = $serialNextId;

        return $this;
    }

    public function getPageId(): ?int
    {
        return $this->pageId;
    }

    public function setPageId(int $pageId): static
    {
        $this->pageId = $pageId;

        return $this;
    }

    public function getSerialCreatedAt(): ?\DateTime
    {
        return $this->serialCreatedAt;
    }

    public function setSerialCreatedAt(\DateTime $serialCreatedAt): static
    {
        $this->serialCreatedAt = $serialCreatedAt;

        return $this;
    }

    public function getSerialLastModifiedAt(): ?\DateTime
    {
        return $this->serialLastModifiedAt;
    }

    public function setSerialLastModifiedAt(\DateTime $serialLastModifiedAt): static
    {
        $this->serialLastModifiedAt = $serialLastModifiedAt;

        return $this;
    }

    public function getSerialLastModifier(): ?ManagementUsers
    {
        return $this->serialLastModifier;
    }

    public function setSerialLastModifier(?ManagementUsers $serialLastModifier): static
    {
        $this->serialLastModifier = $serialLastModifier;

        return $this;
    }

    public function getSeason(): ?ApiSeasons
    {
        return $this->season;
    }

    public function setSeason(?ApiSeasons $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getSerialOwner(): ?ManagementUsers
    {
        return $this->serialOwner;
    }

    public function setSerialOwner(?ManagementUsers $serialOwner): static
    {
        $this->serialOwner = $serialOwner;

        return $this;
    }


}
