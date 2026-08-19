<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ManagementComponents
 */
#[ORM\Table(name: 'management__components')]
#[ORM\Index(name: 'Owner_Id_idx', columns: ['component_Owner_Id'])]
#[ORM\Index(name: 'Last_Modifier_idx', columns: ['component_Last_modifier'])]
#[ORM\Entity]
class ManagementComponents
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'component_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $componentId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'component_Name', type: 'string', length: 45, nullable: true)]
    private $componentName;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'component_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $componentCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'component_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $componentLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'component_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $componentOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'component_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $componentLastModifier;

    public function getComponentId(): ?int
    {
        return $this->componentId;
    }

    public function getComponentName(): ?string
    {
        return $this->componentName;
    }

    public function setComponentName(?string $componentName): static
    {
        $this->componentName = $componentName;

        return $this;
    }

    public function getComponentCreatedAt(): ?\DateTime
    {
        return $this->componentCreatedAt;
    }

    public function setComponentCreatedAt(\DateTime $componentCreatedAt): static
    {
        $this->componentCreatedAt = $componentCreatedAt;

        return $this;
    }

    public function getComponentLastModifiedAt(): ?\DateTime
    {
        return $this->componentLastModifiedAt;
    }

    public function setComponentLastModifiedAt(\DateTime $componentLastModifiedAt): static
    {
        $this->componentLastModifiedAt = $componentLastModifiedAt;

        return $this;
    }

    public function getComponentOwner(): ?ManagementUsers
    {
        return $this->componentOwner;
    }

    public function setComponentOwner(?ManagementUsers $componentOwner): static
    {
        $this->componentOwner = $componentOwner;

        return $this;
    }

    public function getComponentLastModifier(): ?ManagementUsers
    {
        return $this->componentLastModifier;
    }

    public function setComponentLastModifier(?ManagementUsers $componentLastModifier): static
    {
        $this->componentLastModifier = $componentLastModifier;

        return $this;
    }


}
