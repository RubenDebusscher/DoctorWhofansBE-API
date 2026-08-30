<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * V2ApiItems
 */
#[ORM\Table(name: 'V2__Api__Items')]
#[ORM\Index(name: 'Last_modifier_idx', columns: ['apiI_Last_modifier'])]
#[ORM\Index(name: 'type_idx', columns: ['apiI_Type'])]
#[ORM\Index(name: 'page_idx', columns: ['apiI_Page'])]
#[ORM\Index(name: 'Creator_idx', columns: ['apiI_Owner_Id'])]
#[ORM\Entity]
#[ApiResource(
    shortName: 'V2ApiItem' // Inflector maakt hier netjes /v2-api-items van
)]
class V2ApiItems
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'apiI_id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $apiiId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'apiI_Name', type: 'string', length: 45, nullable: true)]
    private $apiiName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'apiI_Image', type: 'string', length: 45, nullable: false)]
    private $apiiImage;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiI_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $apiiCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiI_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $apiiLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'apiI_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $apiiOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'apiI_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $apiiLastModifier;

    /**
     * @var \ManagementPages
     */
    #[ORM\JoinColumn(name: 'apiI_Page', referencedColumnName: 'page_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementPages::class)]
    private $apiiPage;

    /**
     * @var \V2ApiTypes
     */
    #[ORM\JoinColumn(name: 'apiI_Type', referencedColumnName: 'apiT_Id')]
    #[ORM\ManyToOne(targetEntity: \V2ApiTypes::class)]
    private $apiiType;

    public function getApiiId(): ?int
    {
        return $this->apiiId;
    }

    public function getApiiName(): ?string
    {
        return $this->apiiName;
    }

    public function setApiiName(?string $apiiName): static
    {
        $this->apiiName = $apiiName;

        return $this;
    }

    public function getApiiImage(): ?string
    {
        return $this->apiiImage;
    }

    public function setApiiImage(string $apiiImage): static
    {
        $this->apiiImage = $apiiImage;

        return $this;
    }

    public function getApiiCreatedAt(): ?\DateTime
    {
        return $this->apiiCreatedAt;
    }

    public function setApiiCreatedAt(\DateTime $apiiCreatedAt): static
    {
        $this->apiiCreatedAt = $apiiCreatedAt;

        return $this;
    }

    public function getApiiLastModifiedAt(): ?\DateTime
    {
        return $this->apiiLastModifiedAt;
    }

    public function setApiiLastModifiedAt(\DateTime $apiiLastModifiedAt): static
    {
        $this->apiiLastModifiedAt = $apiiLastModifiedAt;

        return $this;
    }

    public function getApiiOwner(): ?ManagementUsers
    {
        return $this->apiiOwner;
    }

    public function setApiiOwner(?ManagementUsers $apiiOwner): static
    {
        $this->apiiOwner = $apiiOwner;

        return $this;
    }

    public function getApiiLastModifier(): ?ManagementUsers
    {
        return $this->apiiLastModifier;
    }

    public function setApiiLastModifier(?ManagementUsers $apiiLastModifier): static
    {
        $this->apiiLastModifier = $apiiLastModifier;

        return $this;
    }

    public function getApiiPage(): ?ManagementPages
    {
        return $this->apiiPage;
    }

    public function setApiiPage(?ManagementPages $apiiPage): static
    {
        $this->apiiPage = $apiiPage;

        return $this;
    }

    public function getApiiType(): ?V2ApiTypes
    {
        return $this->apiiType;
    }

    public function setApiiType(?V2ApiTypes $apiiType): static
    {
        $this->apiiType = $apiiType;

        return $this;
    }


}
