<?php

namespace App\Entity;

use App\Entity\ManagementUsers;
use App\Entity\V3Attributes;
use App\Entity\V3Items;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * V3Itemattributes
 */
#[ORM\Table(name: 'V3__ItemAttributes')]
#[ORM\Index(name: 'fk_ItemAttributes_Items', columns: ['ItemID'])]
#[ORM\Index(name: 'AttributeID', columns: ['AttributeID'])]
#[ORM\Index(name: 'created_by', columns: ['created_by'])]
#[ORM\Index(name: 'updated_by', columns: ['updated_by'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class V3Itemattributes
{
    /**
     * @var int|null
     */
    #[ORM\Column(name: 'ItemAttributeValueID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail'])]
    private ?int $itemattributevalueid = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'Value', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail', 'v3_item:write'])]
    private ?string $value = null;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'NumberValue', type: 'integer', nullable: true)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail', 'v3_item:write'])]
    private ?int $numbervalue = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(name: 'DateValue', type: 'datetime', nullable: true)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail', 'v3_item:write'])]
    private ?\DateTimeInterface $datevalue = null;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'BoolValue', type: 'boolean', nullable: true)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail', 'v3_item:write'])]
    private ?bool $boolvalue = null;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'LookupValue', type: 'integer', nullable: true)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail', 'v3_item:write'])]
    private ?int $lookupvalue = null;


    /**
     * @var int|null
     */
    #[ORM\Column(name: 'LookupValue2', type: 'integer', nullable: true)]
    #[Groups(['v3_item:detail', 'v3_itemattributes:read'])]
    private ?int $lookupvalue2 = null;

    /**
     * @var ManagementUsers|null
     */
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail'])]
    private ?ManagementUsers $createdBy = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail'])]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var ManagementUsers|null
     */
    #[ORM\JoinColumn(name: 'updated_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail'])]
    private ?ManagementUsers $updatedBy = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail'])]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var V3Items|null
     */
    #[ORM\JoinColumn(name: 'ItemID', referencedColumnName: 'ItemID')]
    #[ORM\ManyToOne(targetEntity: V3Items::class, inversedBy: 'itemAttributes')]
    private ?V3Items $item = null;

    /**
     * @var V3Attributes|null
     */
    #[ORM\JoinColumn(name: 'AttributeID', referencedColumnName: 'AttributeID')]
    #[ORM\ManyToOne(targetEntity: V3Attributes::class)]
    #[Groups(['v3_itemattributes:read', 'v3_item:detail'])]
    private ?V3Attributes $attributeid = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime();
        }
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getItemattributevalueid(): ?int
    {
        return $this->itemattributevalueid;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getNumbervalue(): ?int
    {
        return $this->numbervalue;
    }

    public function setNumbervalue(?int $numbervalue): static
    {
        $this->numbervalue = $numbervalue;
        return $this;
    }

    public function getDatevalue(): ?\DateTimeInterface
    {
        return $this->datevalue;
    }

    public function setDatevalue(?\DateTimeInterface $datevalue): static
    {
        $this->datevalue = $datevalue;
        return $this;
    }

    public function isBoolvalue(): ?bool
    {
        return $this->boolvalue;
    }

    public function setBoolvalue(?bool $boolvalue): static
    {
        $this->boolvalue = $boolvalue;
        return $this;
    }

    public function getLookupvalue(): ?int
    {
        return $this->lookupvalue;
    }

    public function getLookupvalue2(): ?int
    {
        return $this->lookupvalue2;
    }

    public function setLookupvalue(?int $lookupvalue): static
    {
        $this->lookupvalue = $lookupvalue;
        return $this;
    }

    public function setLookupvalue2(?int $lookupvalue2): static
    {
        $this->lookupvalue2 = $lookupvalue2;
        return $this;
    }

    public function getCreatedBy(): ?ManagementUsers
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?ManagementUsers $createdBy): static
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedBy(): ?ManagementUsers
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?ManagementUsers $updatedBy): static
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getItem(): ?V3Items
    {
        return $this->item;
    }

    public function setItem(?V3Items $item): static
    {
        $this->item = $item;
        return $this;
    }

    public function getAttributeid(): ?V3Attributes
    {
        return $this->attributeid;
    }

    public function setAttributeid(?V3Attributes $attributeid): static
    {
        $this->attributeid = $attributeid;
        return $this;
    }
}
