<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiActors
 */
#[ORM\Table(name: 'api__actors')]
#[ORM\Index(name: 'API__Actors__Owner__ID', columns: ['actor_Owner_Id'])]
#[ORM\Index(name: 'API__Actors__Last__Modified__User__ID', columns: ['actor_Last_modifier'])]
#[ORM\Entity]
class ApiActors
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'actor_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $actorId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'actor_First_name', type: 'text', length: 65535, nullable: false)]
    private $actorFirstName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'actor_Last_name', type: 'string', length: 45, nullable: false)]
    private $actorLastName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'actor_gender', type: 'string', length: 10, nullable: false)]
    private $actorGender;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'actor_Birthdate', type: 'date', nullable: false)]
    private $actorBirthdate;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'actor_Deathdate', type: 'date', nullable: true)]
    private $actorDeathdate;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'actor_Image', type: 'text', length: 0, nullable: true)]
    private $actorImage;

    /**
     * @var int
     */
    #[ORM\Column(name: 'actor_Page_Id', type: 'integer', nullable: false)]
    private $actorPageId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'actor_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $actorCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'actor_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $actorLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'actor_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $actorOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'actor_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $actorLastModifier;

    public function getActorId(): ?int
    {
        return $this->actorId;
    }

    public function getActorFirstName(): ?string
    {
        return $this->actorFirstName;
    }

    public function setActorFirstName(string $actorFirstName): static
    {
        $this->actorFirstName = $actorFirstName;

        return $this;
    }

    public function getActorLastName(): ?string
    {
        return $this->actorLastName;
    }

    public function setActorLastName(string $actorLastName): static
    {
        $this->actorLastName = $actorLastName;

        return $this;
    }

    public function getActorGender(): ?string
    {
        return $this->actorGender;
    }

    public function setActorGender(string $actorGender): static
    {
        $this->actorGender = $actorGender;

        return $this;
    }

    public function getActorBirthdate(): ?\DateTime
    {
        return $this->actorBirthdate;
    }

    public function setActorBirthdate(\DateTime $actorBirthdate): static
    {
        $this->actorBirthdate = $actorBirthdate;

        return $this;
    }

    public function getActorDeathdate(): ?\DateTime
    {
        return $this->actorDeathdate;
    }

    public function setActorDeathdate(?\DateTime $actorDeathdate): static
    {
        $this->actorDeathdate = $actorDeathdate;

        return $this;
    }

    public function getActorImage(): ?string
    {
        return $this->actorImage;
    }

    public function setActorImage(?string $actorImage): static
    {
        $this->actorImage = $actorImage;

        return $this;
    }

    public function getActorPageId(): ?int
    {
        return $this->actorPageId;
    }

    public function setActorPageId(int $actorPageId): static
    {
        $this->actorPageId = $actorPageId;

        return $this;
    }

    public function getActorCreatedAt(): ?\DateTime
    {
        return $this->actorCreatedAt;
    }

    public function setActorCreatedAt(\DateTime $actorCreatedAt): static
    {
        $this->actorCreatedAt = $actorCreatedAt;

        return $this;
    }

    public function getActorLastModifiedAt(): ?\DateTime
    {
        return $this->actorLastModifiedAt;
    }

    public function setActorLastModifiedAt(\DateTime $actorLastModifiedAt): static
    {
        $this->actorLastModifiedAt = $actorLastModifiedAt;

        return $this;
    }

    public function getActorOwner(): ?ManagementUsers
    {
        return $this->actorOwner;
    }

    public function setActorOwner(?ManagementUsers $actorOwner): static
    {
        $this->actorOwner = $actorOwner;

        return $this;
    }

    public function getActorLastModifier(): ?ManagementUsers
    {
        return $this->actorLastModifier;
    }

    public function setActorLastModifier(?ManagementUsers $actorLastModifier): static
    {
        $this->actorLastModifier = $actorLastModifier;

        return $this;
    }


}
