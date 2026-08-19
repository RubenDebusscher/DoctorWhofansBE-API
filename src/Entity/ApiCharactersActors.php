<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ApiCharactersActors
 */
#[ORM\Table(name: 'api__characters_actors')]
#[ORM\Index(name: 'API__serials_companions__Last__Modified__User__ID', columns: ['AC_Last_modifier'])]
#[ORM\Index(name: 'api__serials_characters_ibfk_40_idx', columns: ['AC_Character_Id'])]
#[ORM\Index(name: 'actorfk', columns: ['AC_actor_Id'])]
#[ORM\Index(name: 'API__serials_companions__Owner__ID', columns: ['AC_Owner_Id'])]
#[ORM\Entity]
class ApiCharactersActors
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'AC_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $acId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'AC_Type', type: 'string', length: 48, nullable: true, options: ['default' => 'Primary TV Role'])]
    private $acType = 'Primary TV Role';

    /**
     * @var int
     */
    #[ORM\Column(name: 'AC_Owner_Id', type: 'integer', nullable: false)]
    private $acOwnerId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'AC_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $acCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     */
    #[ORM\Column(name: 'AC_Last_modifier', type: 'integer', nullable: false)]
    private $acLastModifier;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'AC_Last_modified', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $acLastModified = 'CURRENT_TIMESTAMP';

    /**
     * @var \ApiCharacters
     */
    #[ORM\JoinColumn(name: 'AC_Character_Id', referencedColumnName: 'character_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiCharacters::class)]
    private $acCharacter;

    /**
     * @var \ApiActors
     */
    #[ORM\JoinColumn(name: 'AC_actor_Id', referencedColumnName: 'actor_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiActors::class)]
    private $acActor;

    public function getAcId(): ?int
    {
        return $this->acId;
    }

    public function getAcType(): ?string
    {
        return $this->acType;
    }

    public function setAcType(?string $acType): static
    {
        $this->acType = $acType;

        return $this;
    }

    public function getAcOwnerId(): ?int
    {
        return $this->acOwnerId;
    }

    public function setAcOwnerId(int $acOwnerId): static
    {
        $this->acOwnerId = $acOwnerId;

        return $this;
    }

    public function getAcCreatedAt(): ?\DateTime
    {
        return $this->acCreatedAt;
    }

    public function setAcCreatedAt(\DateTime $acCreatedAt): static
    {
        $this->acCreatedAt = $acCreatedAt;

        return $this;
    }

    public function getAcLastModifier(): ?int
    {
        return $this->acLastModifier;
    }

    public function setAcLastModifier(int $acLastModifier): static
    {
        $this->acLastModifier = $acLastModifier;

        return $this;
    }

    public function getAcLastModified(): ?\DateTime
    {
        return $this->acLastModified;
    }

    public function setAcLastModified(\DateTime $acLastModified): static
    {
        $this->acLastModified = $acLastModified;

        return $this;
    }

    public function getAcCharacter(): ?ApiCharacters
    {
        return $this->acCharacter;
    }

    public function setAcCharacter(?ApiCharacters $acCharacter): static
    {
        $this->acCharacter = $acCharacter;

        return $this;
    }

    public function getAcActor(): ?ApiActors
    {
        return $this->acActor;
    }

    public function setAcActor(?ApiActors $acActor): static
    {
        $this->acActor = $acActor;

        return $this;
    }


}
