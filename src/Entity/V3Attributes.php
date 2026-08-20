<?php

namespace App\Entity;



use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

/**
 * V3Attributes
 */
#[ORM\Table(name: 'V3__Attributes')]
#[ORM\Index(name: 'updated_by', columns: ['updated_by'])]
#[ORM\Index(name: 'created_by', columns: ['created_by'])]
#[ORM\Entity]
class V3Attributes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'AttributeID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['v3_item:detail','contenttype:read'])]
    private $attributeid;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Name', type: 'string', length: 255, nullable: false)]
    #[Groups(['v3_item:detail','contenttype:read'])]
    private $name;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'Description', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_item:detail','contenttype:read'])]
    private $description;

    /**
     * @var int
     */
    #[ORM\ManyToOne(targetEntity: V3Validationrules::class)]
    #[ORM\JoinColumn(name: 'ValidationRuleID', referencedColumnName: 'ValidationRuleID')]
    #[Groups(['contenttype:read', 'v3_item:detail'])]
    private ?V3Validationrules $validationrule = null;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'Visibility', type: 'boolean', nullable: false, options: ['default' => '1'])]
    #[Groups(['v3_item:detail','contenttype:read'])]
    private $visibility = true;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'LookupTable', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_item:detail','contenttype:read'])]
    private $lookuptable;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'BaseAttributes', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_item:detail','contenttype:read'])]
    private $baseattributes;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'Template', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_item:detail','contenttype:read'])]
    private $template;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'Repeatable', type: 'boolean', nullable: true)]
    #[Groups(['v3_item:detail','contenttype:read'])]
    private $repeatable;

    /**
     * @var int
     */
    #[ORM\Column(name: 'created_by', type: 'integer', nullable: false)]
    private $createdBy;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $createdAt = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'updated_by', type: 'integer', nullable: false)]
    private $updatedBy;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $updatedAt = null;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    #[ORM\ManyToMany(targetEntity: \App\Entity\V3Contenttypes::class, inversedBy: 'attributeid')]
    #[ORM\JoinTable(
        name: 'V3__AttributeContentTypes',
        joinColumns: [
        new ORM\JoinColumn(name: 'AttributeID', referencedColumnName: 'AttributeID')
        ],
        inverseJoinColumns: [
        new ORM\JoinColumn(name: 'ContentTypeID', referencedColumnName: 'ContentTypeID')
        ]
    )]
    private $contenttypeid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contenttypeid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getAttributeid(): ?int
    {
        return $this->attributeid;
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

    public function getValidationruleid(): ?int
    {
        return $this->validationruleid;
    }

    public function setValidationruleid(int $validationruleid): static
    {
        $this->validationruleid = $validationruleid;

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getLookuptable(): ?string
    {
        return $this->lookuptable;
    }

    public function setLookuptable(?string $lookuptable): static
    {
        $this->lookuptable = $lookuptable;

        return $this;
    }

    public function getBaseattributes(): ?string
    {
        return $this->baseattributes;
    }

    public function setBaseattributes(?string $baseattributes): static
    {
        $this->baseattributes = $baseattributes;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function isRepeatable(): ?bool
    {
        return $this->repeatable;
    }

    public function setRepeatable(?bool $repeatable): static
    {
        $this->repeatable = $repeatable;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): static
    {
        $this->createdBy = $createdBy;

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

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(int $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

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

    /**
     * @return Collection<int, V3Contenttypes>
     */
    public function getContenttypeid(): Collection
    {
        return $this->contenttypeid;
    }

    public function addContenttypeid(V3Contenttypes $contenttypeid): static
    {
        if (!$this->contenttypeid->contains($contenttypeid)) {
            $this->contenttypeid->add($contenttypeid);
        }

        return $this;
    }

    public function removeContenttypeid(V3Contenttypes $contenttypeid): static
    {
        $this->contenttypeid->removeElement($contenttypeid);

        return $this;
    }

    public function getValidationrule(): ?V3Validationrules
    {
        return $this->validationrule;
    }

    public function setValidationrule(?V3Validationrules $validationrule): static
    {
        $this->validationrule = $validationrule;
        return $this;
    }

}
