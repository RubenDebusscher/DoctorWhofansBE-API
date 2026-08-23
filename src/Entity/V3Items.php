<?php

namespace App\Entity;

use App\Repository\V3ItemsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo; // Importeer Gedmo

#[ORM\Table(name: 'V3__Items')]
#[ORM\Index(name: 'Type', columns: ['Type'])]
#[ORM\Index(name: 'created_by', columns: ['created_by'])]
#[ORM\Index(name: 'updated_by', columns: ['updated_by'])]
#[Gedmo\Loggable] // <-- Schakelt audit logging in voor V3Items
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks] // 1. Voeg dit attribuut toe aan de class
class V3Items
{
    #[ORM\Column(name: 'ItemID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['v3_item:list', 'v3_item:detail','v3_itemattributes:read'])]
    private ?int $itemid = null;

    #[ORM\Column(name: 'Name', type: 'string', length: 255, nullable: false)]
    #[Groups(['v3_item:list', 'v3_item:detail', 'v3_item:write'])]
    #[Gedmo\Versioned] // <-- Blijf wijzigingen in 'Name' volgen!
    private ?string $name = null;

    #[ORM\Column(name: 'Image', type: 'string', length: 255, nullable: false)]
    #[Groups(['v3_item:list', 'v3_item:detail', 'v3_item:write'])]
    #[Gedmo\Versioned] // <-- Blijf wijzigingen in 'Name' volgen!
    private ?string $image = null;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    #[Groups(['v3_item:detail'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: false)]
    #[Groups(['v3_item:detail'])]
    #[Gedmo\Versioned] // <-- Blijf wijzigingen in 'Name' volgen!
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\JoinColumn(name: 'Type', referencedColumnName: 'ContentTypeID')]
    #[ORM\ManyToOne(targetEntity: \V3Contenttypes::class)]
    #[Groups(['v3_item:detail', 'v3_item:write','v3_item:list'])]
    #[Gedmo\Versioned] // <-- Blijf wijzigingen in 'Name' volgen!
    private ?V3Contenttypes $type = null;

    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    #[Groups(['v3_item:detail'])]
    private ?ManagementUsers $createdBy = null;

    #[ORM\JoinColumn(name: 'updated_by', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    #[Groups(['v3_item:detail'])]
    #[Gedmo\Versioned] // <-- Blijf wijzigingen in 'Name' volgen!
    private ?ManagementUsers $updatedBy = null;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: V3Itemattributes::class, cascade: ['persist', 'remove'])]
    #[Groups(['v3_item:detail', 'v3_item:write'])]
    private Collection $itemAttributes;

    public function __construct()
    {
        $this->itemAttributes = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }



    #[ORM\PrePersist] // 2. Wordt 1x uitgevoerd bij het EERSTE aanmaken
    public function onPrePersist(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate] // 3. Wordt automatisch uitgevoerd bij ELKE latere opslagactie
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getItemid(): ?int
    {
        return $this->itemid;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getType(): ?V3Contenttypes
    {
        return $this->type;
    }

    public function setType(?V3Contenttypes $type): static
    {
        $this->type = $type;
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

    public function getUpdatedBy(): ?ManagementUsers
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?ManagementUsers $updatedBy): static
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    /**
     * @return Collection<int, V3Itemattributes>
     */
    public function getItemAttributes(): Collection
    {
        return $this->itemAttributes;
    }

    public function addItemAttribute(V3Itemattributes $itemAttribute): static
    {
        if (!$this->itemAttributes->contains($itemAttribute)) {
            $this->itemAttributes->add($itemAttribute);
            $itemAttribute->setItem($this);
        }

        return $this;
    }

    public function removeItemAttribute(V3Itemattributes $itemAttribute): static
    {
        if ($this->itemAttributes->removeElement($itemAttribute)) {
            if ($itemAttribute->getItem() === $this) {
                $itemAttribute->setItem(null);
            }
        }

        return $this;
    }
}
