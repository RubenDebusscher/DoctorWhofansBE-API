<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ManagementTypes
 */
#[ORM\Table(name: 'management__types')]
#[ORM\Index(name: 'Type_Owner_idx', columns: ['type_Owner_Id'])]
#[ORM\Index(name: 'Type_Last_Modifier_idx', columns: ['type_Last_modifier'])]
#[ORM\Entity]
class ManagementTypes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'type_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $typeId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'type_Name', type: 'string', length: 500, nullable: false)]
    private $typeName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'type_Description', type: 'string', length: 500, nullable: true)]
    private $typeDescription;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'type_Default_Level', type: 'decimal', precision: 19, scale: 4, nullable: true)]
    private $typeDefaultLevel;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'type_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $typeCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'type_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $typeLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'type_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $typeOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'type_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $typeLastModifier;

    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): static
    {
        $this->typeName = $typeName;

        return $this;
    }

    public function getTypeDescription(): ?string
    {
        return $this->typeDescription;
    }

    public function setTypeDescription(?string $typeDescription): static
    {
        $this->typeDescription = $typeDescription;

        return $this;
    }

    public function getTypeDefaultLevel(): ?string
    {
        return $this->typeDefaultLevel;
    }

    public function setTypeDefaultLevel(?string $typeDefaultLevel): static
    {
        $this->typeDefaultLevel = $typeDefaultLevel;

        return $this;
    }

    public function getTypeCreatedAt(): ?\DateTime
    {
        return $this->typeCreatedAt;
    }

    public function setTypeCreatedAt(\DateTime $typeCreatedAt): static
    {
        $this->typeCreatedAt = $typeCreatedAt;

        return $this;
    }

    public function getTypeLastModifiedAt(): ?\DateTime
    {
        return $this->typeLastModifiedAt;
    }

    public function setTypeLastModifiedAt(\DateTime $typeLastModifiedAt): static
    {
        $this->typeLastModifiedAt = $typeLastModifiedAt;

        return $this;
    }

    public function getTypeOwner(): ?ManagementUsers
    {
        return $this->typeOwner;
    }

    public function setTypeOwner(?ManagementUsers $typeOwner): static
    {
        $this->typeOwner = $typeOwner;

        return $this;
    }

    public function getTypeLastModifier(): ?ManagementUsers
    {
        return $this->typeLastModifier;
    }

    public function setTypeLastModifier(?ManagementUsers $typeLastModifier): static
    {
        $this->typeLastModifier = $typeLastModifier;

        return $this;
    }


}
