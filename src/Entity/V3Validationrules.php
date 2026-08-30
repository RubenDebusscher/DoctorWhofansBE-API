<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

/**
 * V3Validationrules
 */
#[ORM\Table(name: 'V3__Validationrules')]
#[ORM\Index(name: 'created_by', columns: ['created_by'])]
#[ORM\Index(name: 'updated_by', columns: ['updated_by'])]
#[ORM\Entity]
#[ApiResource(
    // Hiermee dwing je exact het pad af zonder dat API Platform er 's' aan toevoegt:
    operations: [
        new GetCollection(uriTemplate: '/v3-validationrules'),
        new Get(uriTemplate: '/v3-validationrules/{id}'),
        new Post(uriTemplate: '/v3-validationrules'),
        new Put(uriTemplate: '/v3-validationrules/{id}'),
        new Delete(uriTemplate: '/v3-validationrules/{id}'),
    ]
)]
class V3Validationrules
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'ValidationRuleID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $validationruleid;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Name', type: 'string', length: 255, nullable: false)]
    #[Groups(['contenttype:read', 'v3_item:detail','v3_attributes:read'])]

    private $name;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'Description', type: 'text', length: 65535, nullable: true)]
    #[Groups(['contenttype:read', 'v3_item:detail','v3_attributes:read'])]
    private $description;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $createdAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $updatedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'updated_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $updatedBy;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $createdBy;

    public function getValidationruleid(): ?int
    {
        return $this->validationruleid;
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


}
