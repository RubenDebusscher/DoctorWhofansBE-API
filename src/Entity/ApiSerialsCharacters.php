<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ApiSerialsCharacters
 */
#[ORM\Table(name: 'api__serials_characters')]
#[ORM\Index(name: 'serial_id', columns: ['SC_Serial_Id'])]
#[ORM\Index(name: 'API__serials_companions__Owner__ID', columns: ['serials_characters_Owner_Id'])]
#[ORM\Index(name: 'API__serials_companions__Last__Modified__User__ID', columns: ['serials_characters_Last_modifier'])]
#[ORM\Index(name: 'api__serials_characters_ibfk_1', columns: ['SC_Character_Id'])]
#[ORM\Entity]
class ApiSerialsCharacters
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'SCA_ID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $scaId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'SC_Type', type: 'string', length: 48, nullable: true, options: ['default' => 'Regular'])]
    private $scType = 'Regular';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serials_characters_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $serialsCharactersCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serials_characters_Last_modified', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $serialsCharactersLastModified = 'CURRENT_TIMESTAMP';

    /**
     * @var \ApiCharacters
     */
    #[ORM\JoinColumn(name: 'SC_Character_Id', referencedColumnName: 'character_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiCharacters::class)]
    private $scCharacter;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'serials_characters_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $serialsCharactersLastModifier;

    /**
     * @var \ApiSerials
     */
    #[ORM\JoinColumn(name: 'SC_Serial_Id', referencedColumnName: 'serial_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiSerials::class)]
    private $scSerial;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'serials_characters_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $serialsCharactersOwner;

    public function getScaId(): ?int
    {
        return $this->scaId;
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

    public function getSerialsCharactersCreatedAt(): ?\DateTime
    {
        return $this->serialsCharactersCreatedAt;
    }

    public function setSerialsCharactersCreatedAt(\DateTime $serialsCharactersCreatedAt): static
    {
        $this->serialsCharactersCreatedAt = $serialsCharactersCreatedAt;

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

    public function getScCharacter(): ?ApiCharacters
    {
        return $this->scCharacter;
    }

    public function setScCharacter(?ApiCharacters $scCharacter): static
    {
        $this->scCharacter = $scCharacter;

        return $this;
    }

    public function getSerialsCharactersLastModifier(): ?ManagementUsers
    {
        return $this->serialsCharactersLastModifier;
    }

    public function setSerialsCharactersLastModifier(?ManagementUsers $serialsCharactersLastModifier): static
    {
        $this->serialsCharactersLastModifier = $serialsCharactersLastModifier;

        return $this;
    }

    public function getScSerial(): ?ApiSerials
    {
        return $this->scSerial;
    }

    public function setScSerial(?ApiSerials $scSerial): static
    {
        $this->scSerial = $scSerial;

        return $this;
    }

    public function getSerialsCharactersOwner(): ?ManagementUsers
    {
        return $this->serialsCharactersOwner;
    }

    public function setSerialsCharactersOwner(?ManagementUsers $serialsCharactersOwner): static
    {
        $this->serialsCharactersOwner = $serialsCharactersOwner;

        return $this;
    }


}
