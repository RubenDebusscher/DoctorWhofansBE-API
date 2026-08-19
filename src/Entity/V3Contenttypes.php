<?php

namespace App\Entity;



use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * V3Contenttypes
 */
#[ORM\Table(name: 'V3__ContentTypes')]
#[ORM\Index(name: 'updated_by', columns: ['updated_by'])]
#[ORM\Index(name: 'created_by', columns: ['created_by'])]
#[ORM\Entity]
class V3Contenttypes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'ContentTypeID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $contenttypeid;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Name', type: 'string', length: 255, nullable: false)]
    #[Groups(['v3_item:detail','v3_item:list'])]
    private $name;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'Description', type: 'text', length: 65535, nullable: true)]
    #[Groups(['v3_item:detail'])]
    private $description;

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

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    #[ORM\ManyToMany(targetEntity: \V3Attributes::class, mappedBy: 'contenttypeid')]
    private $attributeid = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributeid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getContenttypeid(): ?int
    {
        return $this->contenttypeid;
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

    /**
     * @return Collection<int, V3Attributes>
     */
    public function getAttributeid(): Collection
    {
        return $this->attributeid;
    }

    public function addAttributeid(V3Attributes $attributeid): static
    {
        if (!$this->attributeid->contains($attributeid)) {
            $this->attributeid->add($attributeid);
            $attributeid->addContenttypeid($this);
        }

        return $this;
    }

    public function removeAttributeid(V3Attributes $attributeid): static
    {
        if ($this->attributeid->removeElement($attributeid)) {
            $attributeid->removeContenttypeid($this);
        }

        return $this;
    }

}
