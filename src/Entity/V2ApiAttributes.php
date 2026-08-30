<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * V2ApiAttributes
 */
#[ORM\Table(name: 'V2__Api_Attributes')]
#[ORM\Index(name: 'apia_Last_modifier', columns: ['apiA_Last_modifier'])]
#[ORM\Index(name: 'apiA_Creator', columns: ['apiA_Owner_Id'])]
#[ORM\Entity]
#[ApiResource(
    shortName: 'V2ApiAttribute'
)]
class V2ApiAttributes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'ApiA_id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $apiaId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'ApiA_Name', type: 'string', length: 45, nullable: true)]
    private $apiaName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'ApiA_description', type: 'string', length: 255, nullable: true)]
    private $apiaDescription;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'ApiA_module', type: 'string', length: 45, nullable: true)]
    private $apiaModule;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'ApiA_repeatable', type: 'boolean', nullable: false)]
    private $apiaRepeatable;

    /**
     * @var int
     */
    #[ORM\Column(name: 'apiA_Owner_Id', type: 'integer', nullable: false)]
    private $apiaOwnerId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiA_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $apiaCreatedAt = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'apiA_Last_modifier', type: 'integer', nullable: false)]
    private $apiaLastModifier;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiA_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $apiaLastModifiedAt = null;

    public function getApiaId(): ?int
    {
        return $this->apiaId;
    }

    public function getApiaName(): ?string
    {
        return $this->apiaName;
    }

    public function setApiaName(?string $apiaName): static
    {
        $this->apiaName = $apiaName;

        return $this;
    }

    public function getApiaDescription(): ?string
    {
        return $this->apiaDescription;
    }

    public function setApiaDescription(?string $apiaDescription): static
    {
        $this->apiaDescription = $apiaDescription;

        return $this;
    }

    public function getApiaModule(): ?string
    {
        return $this->apiaModule;
    }

    public function setApiaModule(?string $apiaModule): static
    {
        $this->apiaModule = $apiaModule;

        return $this;
    }

    public function isApiaRepeatable(): ?bool
    {
        return $this->apiaRepeatable;
    }

    public function setApiaRepeatable(bool $apiaRepeatable): static
    {
        $this->apiaRepeatable = $apiaRepeatable;

        return $this;
    }

    public function getApiaOwnerId(): ?int
    {
        return $this->apiaOwnerId;
    }

    public function setApiaOwnerId(int $apiaOwnerId): static
    {
        $this->apiaOwnerId = $apiaOwnerId;

        return $this;
    }

    public function getApiaCreatedAt(): ?\DateTime
    {
        return $this->apiaCreatedAt;
    }

    public function setApiaCreatedAt(\DateTime $apiaCreatedAt): static
    {
        $this->apiaCreatedAt = $apiaCreatedAt;

        return $this;
    }

    public function getApiaLastModifier(): ?int
    {
        return $this->apiaLastModifier;
    }

    public function setApiaLastModifier(int $apiaLastModifier): static
    {
        $this->apiaLastModifier = $apiaLastModifier;

        return $this;
    }

    public function getApiaLastModifiedAt(): ?\DateTime
    {
        return $this->apiaLastModifiedAt;
    }

    public function setApiaLastModifiedAt(\DateTime $apiaLastModifiedAt): static
    {
        $this->apiaLastModifiedAt = $apiaLastModifiedAt;

        return $this;
    }


}
