<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * V2ApiAttributevalues
 */
#[ORM\Table(name: 'V2__API__AttributeValues')]
#[ORM\Index(name: 'apiAV_Last_modifier', columns: ['apiAV_Last_modifier'])]
#[ORM\Index(name: 'API_AV_Item_idx', columns: ['Item'])]
#[ORM\Index(name: 'API_AV_Attribute_idx', columns: ['Attribute'])]
#[ORM\Index(name: 'apiVA_Creator', columns: ['apiAV_Owner_Id'])]
#[ORM\Entity]
class V2ApiAttributevalues
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'AttributeValue_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $attributevalueId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'TextValue', type: 'text', length: 0, nullable: true)]
    private $textvalue;

    /**
     * @var int
     */
    #[ORM\Column(name: 'LibraryValue', type: 'integer', nullable: false)]
    private $libraryvalue;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiAV_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $apiavCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiAV_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $apiavLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'DateValue', type: 'datetime', nullable: false)]
    private $datevalue;

    /**
     * @var int
     */
    #[ORM\Column(name: 'LinkValue', type: 'integer', nullable: false)]
    private $linkvalue;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'apiAV_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $apiavLastModifier;

    /**
     * @var \V2ApiAttributes
     */
    #[ORM\JoinColumn(name: 'Attribute', referencedColumnName: 'ApiA_id')]
    #[ORM\ManyToOne(targetEntity: \V2ApiAttributes::class)]
    private $attribute;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'apiAV_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $apiavOwner;

    /**
     * @var \V2ApiItems
     */
    #[ORM\JoinColumn(name: 'Item', referencedColumnName: 'apiI_id')]
    #[ORM\ManyToOne(targetEntity: \V2ApiItems::class)]
    private $item;

    public function getAttributevalueId(): ?int
    {
        return $this->attributevalueId;
    }

    public function getTextvalue(): ?string
    {
        return $this->textvalue;
    }

    public function setTextvalue(?string $textvalue): static
    {
        $this->textvalue = $textvalue;

        return $this;
    }

    public function getLibraryvalue(): ?int
    {
        return $this->libraryvalue;
    }

    public function setLibraryvalue(int $libraryvalue): static
    {
        $this->libraryvalue = $libraryvalue;

        return $this;
    }

    public function getApiavCreatedAt(): ?\DateTime
    {
        return $this->apiavCreatedAt;
    }

    public function setApiavCreatedAt(\DateTime $apiavCreatedAt): static
    {
        $this->apiavCreatedAt = $apiavCreatedAt;

        return $this;
    }

    public function getApiavLastModifiedAt(): ?\DateTime
    {
        return $this->apiavLastModifiedAt;
    }

    public function setApiavLastModifiedAt(\DateTime $apiavLastModifiedAt): static
    {
        $this->apiavLastModifiedAt = $apiavLastModifiedAt;

        return $this;
    }

    public function getDatevalue(): ?\DateTime
    {
        return $this->datevalue;
    }

    public function setDatevalue(\DateTime $datevalue): static
    {
        $this->datevalue = $datevalue;

        return $this;
    }

    public function getLinkvalue(): ?int
    {
        return $this->linkvalue;
    }

    public function setLinkvalue(int $linkvalue): static
    {
        $this->linkvalue = $linkvalue;

        return $this;
    }

    public function getApiavLastModifier(): ?ManagementUsers
    {
        return $this->apiavLastModifier;
    }

    public function setApiavLastModifier(?ManagementUsers $apiavLastModifier): static
    {
        $this->apiavLastModifier = $apiavLastModifier;

        return $this;
    }

    public function getAttribute(): ?V2ApiAttributes
    {
        return $this->attribute;
    }

    public function setAttribute(?V2ApiAttributes $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getApiavOwner(): ?ManagementUsers
    {
        return $this->apiavOwner;
    }

    public function setApiavOwner(?ManagementUsers $apiavOwner): static
    {
        $this->apiavOwner = $apiavOwner;

        return $this;
    }

    public function getItem(): ?V2ApiItems
    {
        return $this->item;
    }

    public function setItem(?V2ApiItems $item): static
    {
        $this->item = $item;

        return $this;
    }


}
