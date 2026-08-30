<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Table(name: 'V3__AttributeContentTypes')]
#[ORM\Entity]
class V3AttributeContentTypes
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: V3Contenttypes::class)]
    #[ORM\JoinColumn(name: 'ContentTypeID', referencedColumnName: 'ContentTypeID', nullable: false, onDelete: 'CASCADE')]
    #[Groups(['v3_item:detail', 'v3_contenttype:read'])]
    private ?V3Contenttypes $contentType = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: V3Attributes::class)]
    #[ORM\JoinColumn(name: 'AttributeID', referencedColumnName: 'AttributeID', nullable: false, onDelete: 'CASCADE')]
    #[Groups(['v3_item:detail', 'v3_contenttype:read','contenttype:read'])]
    private ?V3Attributes $attribute = null;

    #[ORM\Column(name: 'display_order', type: 'integer', options: ['default' => 0])]
    #[Groups(['v3_item:detail', 'v3_contenttype:read','contenttype:read'])]
    private int $displayOrder = 0;

    public function getContentType(): ?V3Contenttypes
    {
        return $this->contentType;
    }
    public function setContentType(?V3Contenttypes $contentType): static
    {
        $this->contentType = $contentType; return $this;
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
