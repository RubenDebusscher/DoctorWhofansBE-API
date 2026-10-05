<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Table(name: 'V3__AttributeValidationRules')]
#[ORM\Entity]
class V3AttributeValidationRules
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: V3Attributes::class)]
    #[ORM\JoinColumn(name: 'AttributeID', referencedColumnName: 'AttributeID', nullable: false, onDelete: 'CASCADE')]
    private ?V3Attributes $attribute = null;

    #[ORM\Id]
    // 1. TargetEntity wijst NU naar Code (i.p.v. V3Validationrules)
    #[ORM\ManyToOne(targetEntity: Code::class)]
    // 2. Kolomnaam in DB blijft gewoon ValidationRuleID, maar wijst naar 'id' in v3_Code
    #[ORM\JoinColumn(name: 'ValidationRuleID', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Groups(['v3_attributes:read', 'contenttype:read', 'v3_item:detail', 'v3_itemattributes:read','page:details'])]
    private ?Code $validationRule = null;

    public function getAttribute(): ?V3Attributes
    {
        return $this->attribute;
    }

    public function setAttribute(?V3Attributes $attribute): static
    {
        $this->attribute = $attribute;
        return $this;
    }

    // De getter/setter houden gewoon de vertrouwde naam!
    public function getValidationRule(): ?Code
    {
        return $this->validationRule;
    }

    public function setValidationRule(?Code $validationRule): static
    {
        $this->validationRule = $validationRule;
        return $this;
    }
}
