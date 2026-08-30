<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiCharacters
 */
#[ORM\Table(name: 'api__characters')]
#[ORM\Index(name: 'api__companions_ibfk_3', columns: ['character_Last_modifier'])]
#[ORM\Index(name: 'character_Type', columns: ['character_Type'])]
#[ORM\Index(name: 'actor', columns: ['character_Actor_Id'])]
#[ORM\Index(name: 'API__Companions__Owner__ID', columns: ['character_Owner_Id'])]
#[ORM\Entity]
class ApiCharacters
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'character_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $characterId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'character_First_name', type: 'string', length: 40, nullable: true)]
    private $characterFirstName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'character_Last_name', type: 'string', length: 45, nullable: true)]
    private $characterLastName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'character_Species', type: 'text', length: 16777215, nullable: true)]
    private $characterSpecies;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'character_Image', type: 'text', length: 0, nullable: true)]
    private $characterImage;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'character_Actor_Id', type: 'integer', nullable: true)]
    private $characterActorId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'character_Page_Id', type: 'integer', nullable: false)]
    private $characterPageId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'character_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $characterCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'character_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $characterLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'character_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $characterOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'character_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $characterLastModifier;

    /**
     * @var \ApiCharacterTypes
     */
    #[ORM\JoinColumn(name: 'character_Type', referencedColumnName: 'CT_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiCharacterTypes::class)]
    private $characterType;

    public function getCharacterId(): ?int
    {
        return $this->characterId;
    }

    public function getCharacterFirstName(): ?string
    {
        return $this->characterFirstName;
    }

    public function setCharacterFirstName(?string $characterFirstName): static
    {
        $this->characterFirstName = $characterFirstName;

        return $this;
    }

    public function getCharacterLastName(): ?string
    {
        return $this->characterLastName;
    }

    public function setCharacterLastName(?string $characterLastName): static
    {
        $this->characterLastName = $characterLastName;

        return $this;
    }

    public function getCharacterSpecies(): ?string
    {
        return $this->characterSpecies;
    }

    public function setCharacterSpecies(?string $characterSpecies): static
    {
        $this->characterSpecies = $characterSpecies;

        return $this;
    }

    public function getCharacterImage(): ?string
    {
        return $this->characterImage;
    }

    public function setCharacterImage(?string $characterImage): static
    {
        $this->characterImage = $characterImage;

        return $this;
    }

    public function getCharacterActorId(): ?int
    {
        return $this->characterActorId;
    }

    public function setCharacterActorId(?int $characterActorId): static
    {
        $this->characterActorId = $characterActorId;

        return $this;
    }

    public function getCharacterPageId(): ?int
    {
        return $this->characterPageId;
    }

    public function setCharacterPageId(int $characterPageId): static
    {
        $this->characterPageId = $characterPageId;

        return $this;
    }

    public function getCharacterCreatedAt(): ?\DateTime
    {
        return $this->characterCreatedAt;
    }

    public function setCharacterCreatedAt(\DateTime $characterCreatedAt): static
    {
        $this->characterCreatedAt = $characterCreatedAt;

        return $this;
    }

    public function getCharacterLastModifiedAt(): ?\DateTime
    {
        return $this->characterLastModifiedAt;
    }

    public function setCharacterLastModifiedAt(\DateTime $characterLastModifiedAt): static
    {
        $this->characterLastModifiedAt = $characterLastModifiedAt;

        return $this;
    }

    public function getCharacterOwner(): ?ManagementUsers
    {
        return $this->characterOwner;
    }

    public function setCharacterOwner(?ManagementUsers $characterOwner): static
    {
        $this->characterOwner = $characterOwner;

        return $this;
    }

    public function getCharacterLastModifier(): ?ManagementUsers
    {
        return $this->characterLastModifier;
    }

    public function setCharacterLastModifier(?ManagementUsers $characterLastModifier): static
    {
        $this->characterLastModifier = $characterLastModifier;

        return $this;
    }

    public function getCharacterType(): ?ApiCharacterTypes
    {
        return $this->characterType;
    }

    public function setCharacterType(?ApiCharacterTypes $characterType): static
    {
        $this->characterType = $characterType;

        return $this;
    }


}
