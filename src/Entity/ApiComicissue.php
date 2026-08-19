<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiComicissue
 */
#[ORM\Table(name: 'api__comicissue')]
#[ORM\Index(name: 'page', columns: ['issue_Page_Id'])]
#[ORM\Index(name: 'show', columns: ['Line_id'])]
#[ORM\Index(name: 'API__Actors__Owner__ID', columns: ['issue_Owner_Id'])]
#[ORM\Index(name: 'API__Actors__Last__Modified__User__ID', columns: ['issue_Last_modifier'])]
#[ORM\Entity]
class ApiComicissue
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'Issue_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $issueId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'Line_id', type: 'integer', nullable: false)]
    private $lineId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'issue_Name', type: 'text', length: 65535, nullable: true)]
    private $issueName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'issue_Image', type: 'text', length: 0, nullable: true)]
    private $issueImage;

    /**
     * @var float
     */
    #[ORM\Column(name: 'issue_Order', type: 'float', precision: 10, scale: 0, nullable: false)]
    private $issueOrder;

    /**
     * @var int
     */
    #[ORM\Column(name: 'issue_Writer', type: 'integer', nullable: false)]
    private $issueWriter;

    /**
     * @var int
     */
    #[ORM\Column(name: 'issue_Publisher', type: 'integer', nullable: false)]
    private $issuePublisher;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'issue_Release', type: 'date', nullable: false)]
    private $issueRelease;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'issue_Pages', type: 'integer', nullable: true)]
    private $issuePages;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'issue_Page_Id', type: 'integer', nullable: true)]
    private $issuePageId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'issue_Owner_Id', type: 'integer', nullable: false)]
    private $issueOwnerId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'issue_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $issueCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     */
    #[ORM\Column(name: 'issue_Last_modifier', type: 'integer', nullable: false)]
    private $issueLastModifier;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'issue_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $issueLastModifiedAt = 'CURRENT_TIMESTAMP';

    public function getIssueId(): ?int
    {
        return $this->issueId;
    }

    public function getLineId(): ?int
    {
        return $this->lineId;
    }

    public function setLineId(int $lineId): static
    {
        $this->lineId = $lineId;

        return $this;
    }

    public function getIssueName(): ?string
    {
        return $this->issueName;
    }

    public function setIssueName(?string $issueName): static
    {
        $this->issueName = $issueName;

        return $this;
    }

    public function getIssueImage(): ?string
    {
        return $this->issueImage;
    }

    public function setIssueImage(?string $issueImage): static
    {
        $this->issueImage = $issueImage;

        return $this;
    }

    public function getIssueOrder(): ?float
    {
        return $this->issueOrder;
    }

    public function setIssueOrder(float $issueOrder): static
    {
        $this->issueOrder = $issueOrder;

        return $this;
    }

    public function getIssueWriter(): ?int
    {
        return $this->issueWriter;
    }

    public function setIssueWriter(int $issueWriter): static
    {
        $this->issueWriter = $issueWriter;

        return $this;
    }

    public function getIssuePublisher(): ?int
    {
        return $this->issuePublisher;
    }

    public function setIssuePublisher(int $issuePublisher): static
    {
        $this->issuePublisher = $issuePublisher;

        return $this;
    }

    public function getIssueRelease(): ?\DateTime
    {
        return $this->issueRelease;
    }

    public function setIssueRelease(\DateTime $issueRelease): static
    {
        $this->issueRelease = $issueRelease;

        return $this;
    }

    public function getIssuePages(): ?int
    {
        return $this->issuePages;
    }

    public function setIssuePages(?int $issuePages): static
    {
        $this->issuePages = $issuePages;

        return $this;
    }

    public function getIssuePageId(): ?int
    {
        return $this->issuePageId;
    }

    public function setIssuePageId(?int $issuePageId): static
    {
        $this->issuePageId = $issuePageId;

        return $this;
    }

    public function getIssueOwnerId(): ?int
    {
        return $this->issueOwnerId;
    }

    public function setIssueOwnerId(int $issueOwnerId): static
    {
        $this->issueOwnerId = $issueOwnerId;

        return $this;
    }

    public function getIssueCreatedAt(): ?\DateTime
    {
        return $this->issueCreatedAt;
    }

    public function setIssueCreatedAt(\DateTime $issueCreatedAt): static
    {
        $this->issueCreatedAt = $issueCreatedAt;

        return $this;
    }

    public function getIssueLastModifier(): ?int
    {
        return $this->issueLastModifier;
    }

    public function setIssueLastModifier(int $issueLastModifier): static
    {
        $this->issueLastModifier = $issueLastModifier;

        return $this;
    }

    public function getIssueLastModifiedAt(): ?\DateTime
    {
        return $this->issueLastModifiedAt;
    }

    public function setIssueLastModifiedAt(\DateTime $issueLastModifiedAt): static
    {
        $this->issueLastModifiedAt = $issueLastModifiedAt;

        return $this;
    }


}
