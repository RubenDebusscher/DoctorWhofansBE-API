<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Table(name: 'management__pagetypes')]
#[ORM\Index(name: 'Pagetype_Owner_idx', columns: ['pagetype_Owner_Id'])]
#[ORM\Index(name: 'Pagetype_LastModifier_idx', columns: ['pagetype_Last_modifier'])]
#[ORM\Entity]
class ManagementPagetypes implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'pagetype_Id', type: 'integer', nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: 'pagetype_Name', type: 'string', length: 500, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'pagetype_Description', type: 'string', length: 500, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'pagetype_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: 'pagetype_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $lastModifiedAt = null;

    #[ORM\JoinColumn(name: 'pagetype_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    private ?ManagementUsers $owner = null;

    #[ORM\JoinColumn(name: 'pagetype_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    private ?ManagementUsers $lastModifier = null;

    // --- Getters & Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getLastModifiedAt(): ?\DateTimeInterface
    {
        return $this->lastModifiedAt;
    }

    public function setLastModifiedAt(\DateTimeInterface $lastModifiedAt): static
    {
        $this->lastModifiedAt = $lastModifiedAt;
        return $this;
    }

    public function getOwner(): ?ManagementUsers
    {
        return $this->owner;
    }

    public function setOwner(?ManagementUsers $owner): static
    {
        $this->owner = $owner;
        return $this;
    }

    public function getLastModifier(): ?ManagementUsers
    {
        return $this->lastModifier;
    }

    public function setLastModifier(?ManagementUsers $lastModifier): static
    {
        $this->lastModifier = $lastModifier;
        return $this;
    }

    // --- JSON Serialization ---

    public function jsonSerialize(): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'createdAt'      => $this->createdAt?->format('Y-m-d H:i:s'),
            'lastModifiedAt' => $this->lastModifiedAt?->format('Y-m-d H:i:s'),
            'ownerId'        => $this->owner?->getUserId(),
            'lastModifierId' => $this->lastModifier?->getUserId(),
        ];
    }
}
