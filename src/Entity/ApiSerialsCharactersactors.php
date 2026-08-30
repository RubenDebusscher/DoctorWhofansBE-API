<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ApiSerialsCharactersactors
 */
#[ORM\Table(name: 'api__serials_charactersActors')]
#[ORM\Index(name: 'API__serials_companions__Owner__ID', columns: ['serials_characters_Owner_Id'])]
#[ORM\Index(name: 'API__serials_companions__Last__Modified__User__ID', columns: ['serials_characters_Last_modifier'])]
#[ORM\Index(name: 'SC_CA_ID', columns: ['SC_CA_ID'])]
#[ORM\Index(name: 'serial_id', columns: ['SC_Serial_Id'])]
#[ORM\Entity]
class ApiSerialsCharactersactors
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'SCA_ID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $scaId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'SC_Serial_Id', type: 'integer', nullable: false)]
    private $scSerialId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'SCA_Order', type: 'integer', nullable: false)]
    private $scaOrder;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'SC_Type', type: 'string', length: 48, nullable: true, options: ['default' => 'Regular'])]
    private $scType = 'Regular';

    /**
     * @var int
     */
    #[ORM\Column(name: 'serials_characters_Owner_Id', type: 'integer', nullable: false)]
    private $serialsCharactersOwnerId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serials_characters_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $serialsCharactersCreatedAt = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'serials_characters_Last_modifier', type: 'integer', nullable: false)]
    private $serialsCharactersLastModifier;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serials_characters_Last_modified', type: 'datetime', nullable: false, options: ['default' => null])]
    private $serialsCharactersLastModified = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'SC_CA_ID', type: 'integer', nullable: false, options: ['comment' => 'Hold index for the chartacter with actor, for cast lists'])]
    private $scCaId;

    public function getScaId(): ?int
    {
        return $this->scaId;
    }

    public function getScSerialId(): ?int
    {
        return $this->scSerialId;
    }

    public function setScSerialId(int $scSerialId): static
    {
        $this->scSerialId = $scSerialId;

        return $this;
    }

    public function getScaOrder(): ?int
    {
        return $this->scaOrder;
    }

    public function setScaOrder(int $scaOrder): static
    {
        $this->scaOrder = $scaOrder;

        return $this;
    }

    public function getScType(): ?string
    {
        return $this->scType;
    }

    public function setScType(?string $scType): static
    {
        $this->scType = $scType;

        return $this;
    }

    public function getSerialsCharactersOwnerId(): ?int
    {
        return $this->serialsCharactersOwnerId;
    }

    public function setSerialsCharactersOwnerId(int $serialsCharactersOwnerId): static
    {
        $this->serialsCharactersOwnerId = $serialsCharactersOwnerId;

        return $this;
    }

    public function getSerialsCharactersCreatedAt(): ?\DateTime
    {
        return $this->serialsCharactersCreatedAt;
    }

    public function setSerialsCharactersCreatedAt(\DateTime $serialsCharactersCreatedAt): static
    {
        $this->serialsCharactersCreatedAt = $serialsCharactersCreatedAt;

        return $this;
    }

    public function getSerialsCharactersLastModifier(): ?int
    {
        return $this->serialsCharactersLastModifier;
    }

    public function setSerialsCharactersLastModifier(int $serialsCharactersLastModifier): static
    {
        $this->serialsCharactersLastModifier = $serialsCharactersLastModifier;

        return $this;
    }

    public function getSerialsCharactersLastModified(): ?\DateTime
    {
        return $this->serialsCharactersLastModified;
    }

    public function setSerialsCharactersLastModified(\DateTime $serialsCharactersLastModified): static
    {
        $this->serialsCharactersLastModified = $serialsCharactersLastModified;

        return $this;
    }

    public function getScCaId(): ?int
    {
        return $this->scCaId;
    }

    public function setScCaId(int $scCaId): static
    {
        $this->scCaId = $scCaId;

        return $this;
    }


}
