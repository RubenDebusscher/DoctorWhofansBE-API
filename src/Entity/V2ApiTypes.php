<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * V2ApiTypes
 */
#[ORM\Table(name: 'V2__Api__Types')]
#[ORM\Index(name: 'apiT_Creator', columns: ['apiT_Owner_Id'])]
#[ORM\Index(name: 'apiT_Last_modifier', columns: ['apiT_Last_modifier'])]
#[ORM\Entity]
class V2ApiTypes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'apiT_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $apitId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'apiT_Name', type: 'string', length: 45, nullable: true)]
    private $apitName;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiT_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $apitCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiT_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $apitLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'apiT_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $apitOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'apiT_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $apitLastModifier;

    public function getApitId(): ?int
    {
        return $this->apitId;
    }

    public function getApitName(): ?string
    {
        return $this->apitName;
    }

    public function setApitName(?string $apitName): static
    {
        $this->apitName = $apitName;

        return $this;
    }

    public function getApitCreatedAt(): ?\DateTime
    {
        return $this->apitCreatedAt;
    }

    public function setApitCreatedAt(\DateTime $apitCreatedAt): static
    {
        $this->apitCreatedAt = $apitCreatedAt;

        return $this;
    }

    public function getApitLastModifiedAt(): ?\DateTime
    {
        return $this->apitLastModifiedAt;
    }

    public function setApitLastModifiedAt(\DateTime $apitLastModifiedAt): static
    {
        $this->apitLastModifiedAt = $apitLastModifiedAt;

        return $this;
    }

    public function getApitOwner(): ?ManagementUsers
    {
        return $this->apitOwner;
    }

    public function setApitOwner(?ManagementUsers $apitOwner): static
    {
        $this->apitOwner = $apitOwner;

        return $this;
    }

    public function getApitLastModifier(): ?ManagementUsers
    {
        return $this->apitLastModifier;
    }

    public function setApitLastModifier(?ManagementUsers $apitLastModifier): static
    {
        $this->apitLastModifier = $apitLastModifier;

        return $this;
    }


}
