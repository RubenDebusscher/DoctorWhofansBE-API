<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * V3Itemattributes
 */
#[ORM\Table(name: 'V3__ItemAttributes')]
#[ORM\Index(name: 'fk_ItemAttributes_Items', columns: ['ItemID'])]
#[ORM\Index(name: 'AttributeID', columns: ['AttributeID'])]
#[ORM\Entity]
class V3Itemattributes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'ItemAttributeValueID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['v3_item:detail'])]
    private $itemattributevalueid;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'Value', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_item:detail', 'v3_item:write'])]
    private $value;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'NumberValue', type: 'integer', nullable: true)]
    #[Groups(['v3_item:detail', 'v3_item:write'])]
    private $numbervalue;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'DateValue', type: 'datetime', nullable: true)]
    #[Groups(['v3_item:detail', 'v3_item:write'])]
    private $datevalue;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'BoolValue', type: 'boolean', nullable: true)]
    #[Groups(['v3_item:detail', 'v3_item:write'])]
    private $boolvalue;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'LookupValue', type: 'integer', nullable: true)]
    #[Groups(['v3_item:detail', 'v3_item:write'])]
    private $lookupvalue;

    /**
     * @var int
     */
    #[ORM\Column(name: 'created_by', type: 'integer', nullable: false)]
    private $createdBy;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $createdAt = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'updated_by', type: 'integer', nullable: false)]
    private $updatedBy;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $updatedAt = null;

    /**
     * @var \V3Items
     */
    #[ORM\JoinColumn(name: 'ItemID', referencedColumnName: 'ItemID')]
    #[ORM\ManyToOne(targetEntity: V3Items::class)]
    private $item;

    /**
     * @var \V3Attributes
     */
    #[ORM\JoinColumn(name: 'AttributeID', referencedColumnName: 'AttributeID')]
    #[ORM\ManyToOne(targetEntity: V3Attributes::class)]
    #[Groups(['v3_item:detail'])]
    private $attributeid;

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

    public function getDatevalue(): ?\DateTime
    {
        return $this->datevalue;
    }

    public function setDatevalue(?\DateTime $datevalue): static
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

    public function setLookupvalue(?int $lookupvalue): static
    {
        $this->lookupvalue = $lookupvalue;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(int $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
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
