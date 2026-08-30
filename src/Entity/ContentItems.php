<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContentItems
 */
#[ORM\Table(name: 'content__items')]
#[ORM\Index(name: 'waardes_zoeken', columns: ['item_Value'])]
#[ORM\Index(name: 'item_OWner_idx', columns: ['item_Owner_Id'])]
#[ORM\Index(name: 'item_Value', columns: ['item_Value'])]
#[ORM\Index(name: 'item_Last_Modifier_idx', columns: ['item_Last_modifier'])]
#[ORM\Index(name: 'A_Pagina', columns: ['item_Page'])]
#[ORM\Index(name: 'item_Belogns_To', columns: ['item_Belongs_To'])]
#[ORM\Index(name: 'A_Type', columns: ['item_Type'])]
#[ORM\Entity]
class ContentItems
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'item_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $itemId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'item_Value', type: 'text', length: 0, nullable: true)]
    private $itemValue;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'item_Level', type: 'decimal', precision: 19, scale: 4, nullable: true, options: ['default' => '0.0000', 'comment' => 'default should be 0000.000 (the query should include an if statement that says if this value is in the record, use the default value from the item type)'])]
    private $itemLevel = '0.0000';

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'item_Active', type: 'integer', nullable: true)]
    private $itemActive;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'item_Launch', type: 'datetime', nullable: true)]
    private $itemLaunch;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'item_Class', type: 'string', length: 55, nullable: true, options: ['comment' => 'klasse om responsive design ed '])]
    private $itemClass = '';

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'item_Created_at', type: 'datetime', nullable: true, options: ['default' => null])]
    private $itemCreatedAt = null;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'item_Last_modified_at', type: 'datetime', nullable: true, options: ['default' => null])]
    private $itemLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'item_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $itemOwner;

    /**
     * @var \ContentItems
     */
    #[ORM\JoinColumn(name: 'item_Belongs_To', referencedColumnName: 'item_Id')]
    #[ORM\ManyToOne(targetEntity: \ContentItems::class)]
    private $itemBelongsTo;

    /**
     * @var \ManagementPages
     */
    #[ORM\JoinColumn(name: 'item_Page', referencedColumnName: 'page_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementPages::class)]
    private $itemPage;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'item_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $itemLastModifier;

    /**
     * @var \ManagementTypes
     */
    #[ORM\JoinColumn(name: 'item_Type', referencedColumnName: 'type_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementTypes::class)]
    private $itemType;

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function getItemValue(): ?string
    {
        return $this->itemValue;
    }

    public function setItemValue(?string $itemValue): static
    {
        $this->itemValue = $itemValue;

        return $this;
    }

    public function getItemLevel(): ?string
    {
        return $this->itemLevel;
    }

    public function setItemLevel(?string $itemLevel): static
    {
        $this->itemLevel = $itemLevel;

        return $this;
    }

    public function getItemActive(): ?int
    {
        return $this->itemActive;
    }

    public function setItemActive(?int $itemActive): static
    {
        $this->itemActive = $itemActive;

        return $this;
    }

    public function getItemLaunch(): ?\DateTime
    {
        return $this->itemLaunch;
    }

    public function setItemLaunch(?\DateTime $itemLaunch): static
    {
        $this->itemLaunch = $itemLaunch;

        return $this;
    }

    public function getItemClass(): ?string
    {
        return $this->itemClass;
    }

    public function setItemClass(?string $itemClass): static
    {
        $this->itemClass = $itemClass;

        return $this;
    }

    public function getItemCreatedAt(): ?\DateTime
    {
        return $this->itemCreatedAt;
    }

    public function setItemCreatedAt(?\DateTime $itemCreatedAt): static
    {
        $this->itemCreatedAt = $itemCreatedAt;

        return $this;
    }

    public function getItemLastModifiedAt(): ?\DateTime
    {
        return $this->itemLastModifiedAt;
    }

    public function setItemLastModifiedAt(?\DateTime $itemLastModifiedAt): static
    {
        $this->itemLastModifiedAt = $itemLastModifiedAt;

        return $this;
    }

    public function getItemOwner(): ?ManagementUsers
    {
        return $this->itemOwner;
    }

    public function setItemOwner(?ManagementUsers $itemOwner): static
    {
        $this->itemOwner = $itemOwner;

        return $this;
    }

    public function getItemBelongsTo(): ?self
    {
        return $this->itemBelongsTo;
    }

    public function setItemBelongsTo(?self $itemBelongsTo): static
    {
        $this->itemBelongsTo = $itemBelongsTo;

        return $this;
    }

    public function getItemPage(): ?ManagementPages
    {
        return $this->itemPage;
    }

    public function setItemPage(?ManagementPages $itemPage): static
    {
        $this->itemPage = $itemPage;

        return $this;
    }

    public function getItemLastModifier(): ?ManagementUsers
    {
        return $this->itemLastModifier;
    }

    public function setItemLastModifier(?ManagementUsers $itemLastModifier): static
    {
        $this->itemLastModifier = $itemLastModifier;

        return $this;
    }

    public function getItemType(): ?ManagementTypes
    {
        return $this->itemType;
    }

    public function setItemType(?ManagementTypes $itemType): static
    {
        $this->itemType = $itemType;

        return $this;
    }


}
