<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use App\Entity\V3AttributeContentTypes;
use App\Entity\Code;

/**
 * V3Attributes
 */
#[ORM\Table(name: 'V3__Attributes')]
#[ORM\Index(name: 'updated_by', columns: ['updated_by'])]
#[ORM\Index(name: 'created_by', columns: ['created_by'])]
#[ORM\Entity]
class V3Attributes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'AttributeID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['v3_attributes:read', 'v3_item:detail', 'contenttype:read', 'v3_itemattributes:read'])]
    private $attributeid;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Name', type: 'string', length: 255, nullable: false)]
    #[Groups(['v3_attributes:read', 'v3_item:detail', 'contenttype:read', 'v3_itemattributes:read', 'page:details'])]
    private $name;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'Description', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_attributes:read', 'v3_item:detail', 'contenttype:read', 'v3_itemattributes:read', 'page:details'])]
    private $description;

    /**
   * @var V3Codes|null
   */
    #[ORM\ManyToOne(targetEntity: Code::class)]
    #[ORM\JoinColumn(name: 'ValidationRuleID', referencedColumnName: 'id')] // 👈 Verwijst in v3_codes naar kolom 'id'
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'page:details'])]
    private ?Code $validationrule = null;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'Visibility', type: 'boolean', nullable: false, options: ['default' => '1'])]
    #[Groups(['v3_attributes:read', 'v3_item:detail', 'contenttype:read', 'v3_itemattributes:read', 'page:details'])]
    private $visibility = true;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'LookupTable', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_attributes:read', 'v3_item:detail', 'contenttype:read', 'v3_itemattributes:read', 'page:details'])]
    private $lookuptable;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'LookupTable2', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_attributes:read', 'v3_item:detail', 'contenttype:read', 'v3_itemattributes:read', 'page:details'])]
    private ?string $lookuptable2 = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'BaseAttributes', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_attributes:read', 'v3_item:detail', 'contenttype:read', 'v3_itemattributes:read', 'page:details'])]
    private $baseattributes;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'Template', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_attributes:read', 'v3_item:detail', 'contenttype:read', 'v3_itemattributes:read', 'page:details'])]
    private $template;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'Repeatable', type: 'boolean', nullable: true)]
    #[Groups(['v3_attributes:read', 'v3_item:detail', 'contenttype:read', 'v3_itemattributes:read', 'page:details'])]
    private $repeatable;

    /**
     * @var int
     */
    #[ORM\Column(name: 'created_by', type: 'integer', nullable: false)]
    #[Groups(['v3_attributes:read'])]
    private $createdBy;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    #[Groups(['v3_attributes:read'])]
    private $createdAt = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'updated_by', type: 'integer', nullable: false)]
    #[Groups(['v3_attributes:read'])]
    private $updatedBy;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false, options: ['default' => null])]
    #[Groups(['v3_attributes:read'])]
    private $updatedAt = null;

    /**
      * @var Collection<int, V3AttributeContentTypes>
      */
    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: V3AttributeContentTypes::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $attributeContentTypes;

    public function __construct()
    {
        $this->attributeContentTypes = new ArrayCollection();
    }

    public function getAttributeid(): ?int
    {
        return $this->attributeid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getLookuptable(): ?string
    {
        return $this->lookuptable;
    }

    public function setLookuptable(?string $lookuptable): static
    {
        $this->lookuptable = $lookuptable;

        return $this;
    }

    public function getLookuptable2(): ?string
    {
        return $this->lookuptable2;
    }

    public function setLookuptable2(?string $lookuptable2): static
    {
        $this->lookuptable2 = $lookuptable2;

        return $this;
    }

    public function getBaseattributes(): ?string
    {
        return $this->baseattributes;
    }

    public function setBaseattributes(?string $baseattributes): static
    {
        $this->baseattributes = $baseattributes;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function isRepeatable(): ?bool
    {
        return $this->repeatable;
    }

    public function setRepeatable(?bool $repeatable): static
    {
        $this->repeatable = $repeatable;

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

    /**
     * @return Collection<int, V3AttributeContentTypes>
     */
    public function getAttributeContentTypes(): Collection
    {
        return $this->attributeContentTypes;
    }

    public function addAttributeContentType(V3AttributeContentTypes $attributeContentType): static
    {
        if (!$this->attributeContentTypes->contains($attributeContentType)) {
            $this->attributeContentTypes->add($attributeContentType);
            $attributeContentType->setAttribute($this);
        }

        return $this;
    }

    public function removeAttributeContentType(V3AttributeContentTypes $attributeContentType): static
    {
        if ($this->attributeContentTypes->removeElement($attributeContentType)) {
            if ($attributeContentType->getAttribute() === $this) {
                $attributeContentType->setAttribute(null);
            }
        }

        return $this;
    }


    public function getValidationrule(): ?Code
    {
        return $this->validationrule;
    }

    public function setValidationrule(?Code $validationrule): static
    {
        $this->validationrule = $validationrule;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->attributeid;
    }
}
