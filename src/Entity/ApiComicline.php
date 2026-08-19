<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiComicline
 */
#[ORM\Table(name: 'api__comicLine')]
#[ORM\Index(name: 'page', columns: ['line_Page_Id'])]
#[ORM\Index(name: 'show', columns: ['show_id'])]
#[ORM\Index(name: 'API__Actors__Owner__ID', columns: ['line_Owner_Id'])]
#[ORM\Index(name: 'API__Actors__Last__Modified__User__ID', columns: ['line_Last_modifier'])]
#[ORM\Entity]
class ApiComicline
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'Line_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $lineId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'show_id', type: 'integer', nullable: false)]
    private $showId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'line_Name', type: 'text', length: 65535, nullable: false)]
    private $lineName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'line_Image', type: 'text', length: 0, nullable: true)]
    private $lineImage;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'line_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $lineCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'line_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $lineLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'line_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $lineLastModifier;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'line_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $lineOwner;

    /**
     * @var \ManagementPages
     */
    #[ORM\JoinColumn(name: 'line_Page_Id', referencedColumnName: 'page_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementPages::class)]
    private $linePage;

    public function getLineId(): ?int
    {
        return $this->lineId;
    }

    public function getShowId(): ?int
    {
        return $this->showId;
    }

    public function setShowId(int $showId): static
    {
        $this->showId = $showId;

        return $this;
    }

    public function getLineName(): ?string
    {
        return $this->lineName;
    }

    public function setLineName(string $lineName): static
    {
        $this->lineName = $lineName;

        return $this;
    }

    public function getLineImage(): ?string
    {
        return $this->lineImage;
    }

    public function setLineImage(?string $lineImage): static
    {
        $this->lineImage = $lineImage;

        return $this;
    }

    public function getLineCreatedAt(): ?\DateTime
    {
        return $this->lineCreatedAt;
    }

    public function setLineCreatedAt(\DateTime $lineCreatedAt): static
    {
        $this->lineCreatedAt = $lineCreatedAt;

        return $this;
    }

    public function getLineLastModifiedAt(): ?\DateTime
    {
        return $this->lineLastModifiedAt;
    }

    public function setLineLastModifiedAt(\DateTime $lineLastModifiedAt): static
    {
        $this->lineLastModifiedAt = $lineLastModifiedAt;

        return $this;
    }

    public function getLineLastModifier(): ?ManagementUsers
    {
        return $this->lineLastModifier;
    }

    public function setLineLastModifier(?ManagementUsers $lineLastModifier): static
    {
        $this->lineLastModifier = $lineLastModifier;

        return $this;
    }

    public function getLineOwner(): ?ManagementUsers
    {
        return $this->lineOwner;
    }

    public function setLineOwner(?ManagementUsers $lineOwner): static
    {
        $this->lineOwner = $lineOwner;

        return $this;
    }

    public function getLinePage(): ?ManagementPages
    {
        return $this->linePage;
    }

    public function setLinePage(?ManagementPages $linePage): static
    {
        $this->linePage = $linePage;

        return $this;
    }


}
