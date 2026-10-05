<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Table(name: 'V3__AttributeContentTypes')]
#[ORM\Entity]
class V3AttributeContentTypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['v3_item:detail', 'v3_contenttype:read', 'contenttype:read', 'code:read','page:details'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Code::class, inversedBy: 'attributeContentTypes')]
    #[ORM\JoinColumn(name: 'ContentTypeID', referencedColumnName: 'id', nullable: false)]
    private ?Code $code = null;

    #[ORM\ManyToOne(targetEntity: V3Attributes::class, inversedBy: 'attributeContentTypes')]
    #[ORM\JoinColumn(name: 'AttributeID', referencedColumnName: 'AttributeID', nullable: false, onDelete: 'CASCADE')]
    private ?V3Attributes $attribute = null;

    #[ORM\Column(name: 'display_order', type: 'integer', options: ['default' => 0])]
    #[Groups(['v3_item:detail', 'v3_contenttype:read', 'contenttype:read', 'code:read','page:details'])]
    private int $displayOrder = 0;

    // --- Getters & Setters ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?Code
    {
        return $this->code;
    }
    public function setCode(?Code $code): static
    {
        $this->code = $code; return $this;
    }

    public function getAttribute(): ?V3Attributes
    {
        return $this->attribute;
    }
    public function setAttribute(?V3Attributes $attribute): static
    {
        $this->attribute = $attribute; return $this;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }
    public function setDisplayOrder(int $displayOrder): static
    {
        $this->displayOrder = $displayOrder; return $this;
    }
}
