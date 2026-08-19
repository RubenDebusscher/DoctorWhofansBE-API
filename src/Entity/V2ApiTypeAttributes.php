<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * V2ApiTypeAttributes
 */
#[ORM\Table(name: 'V2__Api_Type_Attributes')]
#[ORM\Index(name: 'apiTA_Creator', columns: ['apiTA_Owner_Id'])]
#[ORM\Index(name: 'apiTa_Last_modifier', columns: ['apiTA_Last_modifier'])]
#[ORM\Index(name: 'Type_idx', columns: ['Type_Id'])]
#[ORM\Index(name: 'IDX_F5EA81283B53D1A0', columns: ['Attribute_Id'])]
#[ORM\Entity]
class V2ApiTypeAttributes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'Type_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private $typeId;

    /**
     * @var float|null
     */
    #[ORM\Column(name: 'ApiTA_Order', type: 'float', precision: 10, scale: 0, nullable: true)]
    private $apitaOrder;

    /**
     * @var int
     */
    #[ORM\Column(name: 'apiTA_Owner_Id', type: 'integer', nullable: false)]
    private $apitaOwnerId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiTA_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $apitaCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     */
    #[ORM\Column(name: 'apiTA_Last_modifier', type: 'integer', nullable: false)]
    private $apitaLastModifier;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiTA_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $apitaLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \V2ApiAttributes
     */
    #[ORM\JoinColumn(name: 'Attribute_Id', referencedColumnName: 'ApiA_id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \V2ApiAttributes::class)]
    private $attribute;

    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    public function getApitaOrder(): ?float
    {
        return $this->apitaOrder;
    }

    public function setApitaOrder(?float $apitaOrder): static
    {
        $this->apitaOrder = $apitaOrder;

        return $this;
    }

    public function getApitaOwnerId(): ?int
    {
        return $this->apitaOwnerId;
    }

    public function setApitaOwnerId(int $apitaOwnerId): static
    {
        $this->apitaOwnerId = $apitaOwnerId;

        return $this;
    }

    public function getApitaCreatedAt(): ?\DateTime
    {
        return $this->apitaCreatedAt;
    }

    public function setApitaCreatedAt(\DateTime $apitaCreatedAt): static
    {
        $this->apitaCreatedAt = $apitaCreatedAt;

        return $this;
    }

    public function getApitaLastModifier(): ?int
    {
        return $this->apitaLastModifier;
    }

    public function setApitaLastModifier(int $apitaLastModifier): static
    {
        $this->apitaLastModifier = $apitaLastModifier;

        return $this;
    }

    public function getApitaLastModifiedAt(): ?\DateTime
    {
        return $this->apitaLastModifiedAt;
    }

    public function setApitaLastModifiedAt(\DateTime $apitaLastModifiedAt): static
    {
        $this->apitaLastModifiedAt = $apitaLastModifiedAt;

        return $this;
    }

    public function getAttribute(): ?V2ApiAttributes
    {
        return $this->attribute;
    }

    public function setAttribute(V2ApiAttributes $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }


}
