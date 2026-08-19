<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ManagementPages
 */
#[ORM\Table(name: 'management__pages')]
#[ORM\Index(name: 'Creator_idx', columns: ['page_Owner_Id'])]
#[ORM\Index(name: 'Last_modifier_idx', columns: ['page_Last_modifier'])]
#[ORM\Index(name: 'Page_Type_idx', columns: ['page_Type'])]
#[ORM\Index(name: 'Parent_idx', columns: ['page_Parent_Id'])]
#[ORM\UniqueConstraint(name: 'page_Id', columns: ['page_Id'])]
#[ORM\Entity]
class ManagementPages
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'page_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $pageId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'page_Name', type: 'text', length: 65535, nullable: true)]
    private $pageName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'page_Link', type: 'text', length: 65535, nullable: true)]
    private $pageLink;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'page_Active', type: 'boolean', nullable: true)]
    private $pageActive;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'page_Order', type: 'decimal', precision: 19, scale: 4, nullable: true)]
    private $pageOrder;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'page_Type', type: 'integer', nullable: true)]
    private $pageType;

    /**
     * @var int
     */
    #[ORM\Column(name: 'page_API_Item', type: 'integer', nullable: false, options: ['comment' => 'Het Idit van het API Item van de prefix van de link'])]
    private $pageApiItem;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'page_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $pageCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'page_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $pageLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'page_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $pageLastModifier;

    /**
     * @var \ManagementPages
     */
    #[ORM\JoinColumn(name: 'page_Parent_Id', referencedColumnName: 'page_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementPages::class)]
    private $pageParent;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'page_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $pageOwner;

    public function getPageId(): ?int
    {
        return $this->pageId;
    }

    public function getPageName(): ?string
    {
        return $this->pageName;
    }

    public function setPageName(?string $pageName): static
    {
        $this->pageName = $pageName;

        return $this;
    }

    public function getPageLink(): ?string
    {
        return $this->pageLink;
    }

    public function setPageLink(?string $pageLink): static
    {
        $this->pageLink = $pageLink;

        return $this;
    }

    public function isPageActive(): ?bool
    {
        return $this->pageActive;
    }

    public function setPageActive(?bool $pageActive): static
    {
        $this->pageActive = $pageActive;

        return $this;
    }

    public function getPageOrder(): ?string
    {
        return $this->pageOrder;
    }

    public function setPageOrder(?string $pageOrder): static
    {
        $this->pageOrder = $pageOrder;

        return $this;
    }

    public function getPageType(): ?int
    {
        return $this->pageType;
    }

    public function setPageType(?int $pageType): static
    {
        $this->pageType = $pageType;

        return $this;
    }

    public function getPageApiItem(): ?int
    {
        return $this->pageApiItem;
    }

    public function setPageApiItem(int $pageApiItem): static
    {
        $this->pageApiItem = $pageApiItem;

        return $this;
    }

    public function getPageCreatedAt(): ?\DateTime
    {
        return $this->pageCreatedAt;
    }

    public function setPageCreatedAt(\DateTime $pageCreatedAt): static
    {
        $this->pageCreatedAt = $pageCreatedAt;

        return $this;
    }

    public function getPageLastModifiedAt(): ?\DateTime
    {
        return $this->pageLastModifiedAt;
    }

    public function setPageLastModifiedAt(\DateTime $pageLastModifiedAt): static
    {
        $this->pageLastModifiedAt = $pageLastModifiedAt;

        return $this;
    }

    public function getPageLastModifier(): ?ManagementUsers
    {
        return $this->pageLastModifier;
    }

    public function setPageLastModifier(?ManagementUsers $pageLastModifier): static
    {
        $this->pageLastModifier = $pageLastModifier;

        return $this;
    }

    public function getPageParent(): ?self
    {
        return $this->pageParent;
    }

    public function setPageParent(?self $pageParent): static
    {
        $this->pageParent = $pageParent;

        return $this;
    }

    public function getPageOwner(): ?ManagementUsers
    {
        return $this->pageOwner;
    }

    public function setPageOwner(?ManagementUsers $pageOwner): static
    {
        $this->pageOwner = $pageOwner;

        return $this;
    }


}
