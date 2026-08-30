<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * V3Contenttypes
 */
#[ORM\Table(name: 'V3__ContentTypes')]
#[ORM\Index(name: 'updated_by', columns: ['updated_by'])]
#[ORM\Index(name: 'created_by', columns: ['created_by'])]
#[ORM\Entity]
class V3Contenttypes
{
    #[ORM\Column(name: 'ContentTypeID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['contenttype:read', 'v3_item:list', 'v3_item:detail'])]
    private ?int $contenttypeid = null;

    #[ORM\Column(name: 'Name', type: 'string', length: 255, nullable: false)]
    #[Groups(['v3_item:detail', 'v3_item:list', 'contenttype:read'])]
    private ?string $name = null;

    #[ORM\Column(name: 'Description', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_item:detail', 'contenttype:read'])]
    private ?string $description = null;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private ?\DateTime $updatedAt = null;

    #[ORM\JoinColumn(name: 'updated_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    private ?ManagementUsers $updatedBy = null;

    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    private ?ManagementUsers $createdBy = null;

    /**
     * Nieuwe koppeling via de pivot-entity (V3AttributeContentTypes)
     * Automatisch gesorteerd op displayOrder!
     *
     * @var Collection<int, V3AttributeContentTypes>
     */
    #[ORM\OneToMany(mappedBy: 'contentType', targetEntity: V3AttributeContentTypes::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['displayOrder' => 'ASC'])]
    #[Groups(['contenttype:read', 'v3_item:list', 'v3_item:detail'])]
    private Collection $attributeContentTypes;

    public function __construct()
    {
        $this->attributeContentTypes = new ArrayCollection();
    }

    public function getContenttypeid(): ?int
    {
        return $this->contenttypeid;
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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;
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

    public function getUpdatedBy(): ?ManagementUsers
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?ManagementUsers $updatedBy): static
    {
        $this->updatedBy = $updatedBy;
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
            $attributeContentType->setContentType($this);
        }

        return $this;
    }

    public function removeAttributeContentType(V3AttributeContentTypes $attributeContentType): static
    {
        if ($this->attributeContentTypes->removeElement($attributeContentType)) {
            if ($attributeContentType->getContentType() === $this) {
                $attributeContentType->setContentType(null);
            }
        }

        return $this;
    }
}
