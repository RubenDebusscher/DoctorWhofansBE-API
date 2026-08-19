<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * V3Attributevalidationrules
 */
#[ORM\Table(name: 'V3__AttributeValidationRules')]
#[ORM\Index(name: 'updated_by', columns: ['updated_by'])]
#[ORM\Index(name: 'V3__AttributeValidationRules_ibfk_2', columns: ['ValidationRuleID'])]
#[ORM\Index(name: 'created_by', columns: ['created_by'])]
#[ORM\Index(name: 'IDX_CA9F2EE150B2D108', columns: ['AttributeID'])]
#[ORM\Entity]
class V3Attributevalidationrules
{
    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \V3Attributes
     */
    #[ORM\JoinColumn(name: 'AttributeID', referencedColumnName: 'AttributeID')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \V3Attributes::class)]
    private $attributeid;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'updated_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $updatedBy;

    /**
     * @var \V3Validationrules
     */
    #[ORM\JoinColumn(name: 'ValidationRuleID', referencedColumnName: 'ValidationRuleID')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \V3Validationrules::class)]
    private $validationruleid;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $createdBy;

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

    public function getAttributeid(): ?V3Attributes
    {
        return $this->attributeid;
    }

    public function setAttributeid(V3Attributes $attributeid): static
    {
        $this->attributeid = $attributeid;

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

    public function getValidationruleid(): ?V3Validationrules
    {
        return $this->validationruleid;
    }

    public function setValidationruleid(V3Validationrules $validationruleid): static
    {
        $this->validationruleid = $validationruleid;

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


}
