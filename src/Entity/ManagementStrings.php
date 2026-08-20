<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ManagementStrings
 */
#[ORM\Table(name: 'management__strings')]
#[ORM\Index(name: 'Last_Modifier_idx', columns: ['string_Last_modifier'])]
#[ORM\Index(name: 'Component_idx', columns: ['string_Component_Id'])]
#[ORM\Index(name: 'Owner_idx', columns: ['string_Owner_Id'])]
#[ORM\Entity]
class ManagementStrings
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'string_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $stringId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'string_Key', type: 'string', length: 45, nullable: true)]
    private $stringKey;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'string_Value', type: 'text', length: 0, nullable: true)]
    private $stringValue;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'string_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $stringCreatedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'string_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $stringOwner;

    /**
     * @var \ManagementComponents
     */
    #[ORM\JoinColumn(name: 'string_Component_Id', referencedColumnName: 'component_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementComponents::class)]
    private $stringComponent;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'string_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $stringLastModifier;

    public function getStringId(): ?int
    {
        return $this->stringId;
    }

    public function getStringKey(): ?string
    {
        return $this->stringKey;
    }

    public function setStringKey(?string $stringKey): static
    {
        $this->stringKey = $stringKey;

        return $this;
    }

    public function getStringValue(): ?string
    {
        return $this->stringValue;
    }

    public function setStringValue(?string $stringValue): static
    {
        $this->stringValue = $stringValue;

        return $this;
    }

    public function getStringCreatedAt(): ?\DateTime
    {
        return $this->stringCreatedAt;
    }

    public function setStringCreatedAt(\DateTime $stringCreatedAt): static
    {
        $this->stringCreatedAt = $stringCreatedAt;

        return $this;
    }

    public function getStringOwner(): ?ManagementUsers
    {
        return $this->stringOwner;
    }

    public function setStringOwner(?ManagementUsers $stringOwner): static
    {
        $this->stringOwner = $stringOwner;

        return $this;
    }

    public function getStringComponent(): ?ManagementComponents
    {
        return $this->stringComponent;
    }

    public function setStringComponent(?ManagementComponents $stringComponent): static
    {
        $this->stringComponent = $stringComponent;

        return $this;
    }

    public function getStringLastModifier(): ?ManagementUsers
    {
        return $this->stringLastModifier;
    }

    public function setStringLastModifier(?ManagementUsers $stringLastModifier): static
    {
        $this->stringLastModifier = $stringLastModifier;

        return $this;
    }


}
