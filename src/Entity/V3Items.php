<?php

namespace App\Entity;

use App\Repository\V3ItemsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Table(name: 'V3__Items')]
#[ORM\Index(name: 'Type', columns: ['Type'])]
#[ORM\Index(name: 'created_by', columns: ['created_by'])]
#[ORM\Index(name: 'updated_by', columns: ['updated_by'])]
#[Gedmo\Loggable]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class V3Items
{
    #[ORM\Column(name: 'ItemID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['v3_item:list', 'v3_item:detail', 'v3_itemattributes:read','page:details', 'page:read'])]
    private ?int $itemid = null;

    #[ORM\Column(name: 'Name', type: 'string', length: 255, nullable: false)]
    #[Groups(['v3_item:list', 'v3_item:detail', 'v3_item:write','page:details', 'page:read'])]
    #[Gedmo\Versioned]
    private ?string $name = null;

    #[ORM\Column(name: 'Image', type: 'text', nullable: true)]
    #[Groups(['v3_item:list', 'v3_item:detail', 'v3_item:write','page:details'])]
    #[Gedmo\Versioned]
    private ?string $image = null;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    #[Groups(['v3_item:detail'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: false)]
    #[Groups(['v3_item:detail'])]
    #[Gedmo\Versioned]
    private ?\DateTimeInterface $updatedAt = null;

    // Koppeling naar de Code entity (waarin codeGroup_codeKey = ContentTypes)
    #[ORM\JoinColumn(name: 'Type', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: Code::class, cascade: ['persist'])]
    #[Groups(['v3_item:detail', 'v3_item:write', 'v3_item:list','page:details'])]
    #[Gedmo\Versioned]
    private ?Code $type = null;

    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    #[Groups(['v3_item:detail'])]
    private ?ManagementUsers $createdBy = null;

    #[ORM\JoinColumn(name: 'updated_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    #[Groups(['v3_item:detail'])]
    #[Gedmo\Versioned]
    private ?ManagementUsers $updatedBy = null;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: V3Itemattributes::class, cascade: ['persist', 'remove'])]
    #[Groups(['v3_item:detail', 'v3_item:write','page:details'])]
    private Collection $itemAttributes;

    public function __construct()
    {
        $this->itemAttributes = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getItemid(): ?int
    {
        return $this->itemid;
    }

    public function getId(): ?int
    {
        return $this->itemid;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getType(): ?Code
    {
        return $this->type;
    }

    public function setType(?Code $type): static
    {
        $this->type = $type;
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

    public function getUpdatedBy(): ?ManagementUsers
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?ManagementUsers $updatedBy): static
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    /**
     * @return Collection<int, V3Itemattributes>
     */
    public function getItemAttributes(): Collection
    {
        return $this->itemAttributes;
    }

    public function addItemAttribute(V3Itemattributes $itemAttribute): static
    {
        if (!$this->itemAttributes->contains($itemAttribute)) {
            $this->itemAttributes->add($itemAttribute);
            $itemAttribute->setItem($this);
        }

        return $this;
    }

    public function removeItemAttribute(V3Itemattributes $itemAttribute): static
    {
        if ($this->itemAttributes->removeElement($itemAttribute)) {
            if ($itemAttribute->getItem() === $this) {
                $itemAttribute->setItem(null);
            }
        }

        return $this;
    }

    /**
     * Geeft alle attribute-definities terug die bij de Code (ContentType) van dit item horen,
     * netjes gesorteerd op displayOrder.
     */
    #[Groups(['page:details', 'item:read'])]
    public function getOrderedAttributes(): array
    {
        if (!$this->type) {
            return [];
        }

        $links = $this->type->getAttributeContentTypes()->toArray();

        usort(
            $links, function ($a, $b) {
                return $a->getDisplayOrder() <=> $b->getDisplayOrder();
            }
        );

        return array_map(
            function ($link) {
                return $link->getAttribute();
            }, $links
        );
    }

    /**
     * Telt het totaal aantal gekoppelde attributen op basis van het Type (Code) van dit item.
     */
    #[Groups(['v3_item:list', 'v3_item:detail'])]
    public function getTotalAttributesCount(): int
    {
        if (!$this->type) {
            return 0;
        }

        return $this->type->getAttributeContentTypes()->count();
    }

    /**
     * Telt het aantal UNIEKE ingevulde attributen van dit specifieke item.
     */
    #[Groups(['v3_item:list', 'v3_item:detail'])]
    public function getFilledAttributesCount(): int
    {
        if ($this->itemAttributes->isEmpty()) {
            return 0;
        }

        $countedAttributeIds = [];

        foreach ($this->itemAttributes as $attrValue) {
            $attrId = $attrValue->getAttributeid() ? $attrValue->getAttributeid()->getId() : null;

            if (!$attrId || in_array($attrId, $countedAttributeIds, true)) {
                continue;
            }

            if ($attrValue->getValue() !== null
                || $attrValue->getNumbervalue() !== null
                || $attrValue->getLookupvalue() !== null
                || $attrValue->getDatevalue() !== null
                || $attrValue->getBoolvalue() !== null
            ) {
                $countedAttributeIds[] = $attrId;
            }
        }

        return count($countedAttributeIds);
    }

    /**
     * Berekent het voortgangspercentage op basis van de twee bovenstaande getters.
     */
    #[Groups(['v3_item:list', 'v3_item:detail'])]
    public function getCompletionPercentage(): int
    {
        $total = $this->getTotalAttributesCount();

        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->getFilledAttributesCount() / $total) * 100);
    }

    // In src/Entity/V3Items.php

    /**
     * Zoekt specifiek naar de lookupvalue van een opgegeven attribuut ID.
     */
    public function getParentLookupValue(int $targetAttributeId): ?int
    {
        foreach ($this->getItemAttributes() as $attr) {
            $attrEntity = method_exists($attr, 'getAttributeid') ? $attr->getAttributeid() : null;

            $currentAttrId = is_object($attrEntity) && method_exists($attrEntity, 'getAttributeid')
            ? $attrEntity->getAttributeid()
            : $attrEntity;

            if ((int) $currentAttrId === $targetAttributeId) {
                return $attr->getLookupvalue();
            }
        }

        return null;
    }

    /**
     * Geeft de weergavenaam van het item terug (bijv. de naam of het ID).
     */
    public function getDisplayName(): string
    {
        if (method_exists($this, 'getName') && $this->getName()) {
            return $this->getName();
        }

        return (string) $this->getItemid();
    }





}
