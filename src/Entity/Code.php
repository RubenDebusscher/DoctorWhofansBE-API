<?php

namespace App\Entity;

use App\Repository\CodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CodeRepository::class)]
#[ORM\Table(name: 'codes')]
#[ORM\UniqueConstraint(name: 'unique_group_code', columns: ['code_group_id', 'code_value'])]
class Code
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read','v3_item:list','page:read', 'page:details'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CodeGroup::class, inversedBy: 'codes')]
    #[ORM\JoinColumn(name: 'code_group_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read','v3_item:list','page:read', 'page:details'])]
    private ?CodeGroup $codeGroup = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read','v3_item:list','page:read', 'page:details'])]
    private string $codeValue;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read','v3_item:list','page:read', 'page:details'])]
    private string $label;

    /**
     * Korte naam voor weergave in compacte UI elementen (bijv. badges, tabellen)
     */
    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read','v3_item:list','page:read', 'page:details'])]
    private ?string $shortLabel = null;

    /**
     * Technische documentatie / notities voor ontwikkelaars en beheerders
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read'])]
    private ?string $developerDescription = null;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read'])]
    private int $displayOrder = 0;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read'])]
    private bool $isActive = true;

    /**
     * Optionele einddatum. Na deze datum is de optie verlopen/gedesactiveerd.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read'])]
    private ?\DateTimeInterface $validUntil = null;

    /**
     * Flexibele parameters/extra data opgeslagen als JSON array/object
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read', 'code:read'])]
    private ?array $parameters = null;

    /**
     * @var Collection<int, V3AttributeContentTypes>
     */
    #[ORM\OneToMany(mappedBy: 'code', targetEntity: V3AttributeContentTypes::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['displayOrder' => 'ASC'])]
    #[Groups(['contenttype:read', 'code:read'])]
    private Collection $attributeContentTypes;

    public function __construct()
    {
        $this->attributeContentTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeGroup(): ?CodeGroup
    {
        return $this->codeGroup;
    }

    public function setCodeGroup(?CodeGroup $codeGroup): static
    {
        $this->codeGroup = $codeGroup;
        return $this;
    }

    public function getCodeValue(): string
    {
        return $this->codeValue;
    }

    public function setCodeValue(string $codeValue): static
    {
        $this->codeValue = $codeValue;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    public function getShortLabel(): ?string
    {
        return $this->shortLabel;
    }

    public function setShortLabel(?string $shortLabel): static
    {
        $this->shortLabel = $shortLabel;
        return $this;
    }

    public function getDeveloperDescription(): ?string
    {
        return $this->developerDescription;
    }

    public function setDeveloperDescription(?string $developerDescription): static
    {
        $this->developerDescription = $developerDescription;
        return $this;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): static
    {
        $this->displayOrder = $displayOrder;
        return $this;
    }

    public function isIsActive(): bool
    {
        if ($this->validUntil !== null && $this->validUntil < new \DateTime()) {
            return false;
        }

        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getValidUntil(): ?\DateTimeInterface
    {
        return $this->validUntil;
    }

    public function setValidUntil(?\DateTimeInterface $validUntil): static
    {
        $this->validUntil = $validUntil;
        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function setParameters(?array $parameters): static
    {
        $this->parameters = $parameters;
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
            $attributeContentType->setCode($this);
        }

        return $this;
    }

    public function removeAttributeContentType(V3AttributeContentTypes $attributeContentType): static
    {
        if ($this->attributeContentTypes->removeElement($attributeContentType)) {
            if ($attributeContentType->getCode() === $this) {
                $attributeContentType->setCode(null);
            }
        }

        return $this;
    }
}
