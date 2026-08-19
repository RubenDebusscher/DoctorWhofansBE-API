<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiCrew
 */
#[ORM\Table(name: 'api__crew')]
#[ORM\Index(name: 'API__Actors__Owner__ID', columns: ['crew_Owner_Id'])]
#[ORM\Index(name: 'API__Actors__Last__Modified__User__ID', columns: ['crew_Last_modifier'])]
#[ORM\Entity]
class ApiCrew
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'crew_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $crewId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'crew_First_name', type: 'text', length: 65535, nullable: false)]
    private $crewFirstName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'crew_Middle_name', type: 'string', length: 45, nullable: true)]
    private $crewMiddleName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'crew_Last_name', type: 'string', length: 45, nullable: true)]
    private $crewLastName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'crew_gender', type: 'string', length: 10, nullable: true)]
    private $crewGender;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'crew_Birthdate', type: 'date', nullable: true)]
    private $crewBirthdate;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'crew_Deathdate', type: 'date', nullable: true)]
    private $crewDeathdate;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'crew_Image', type: 'text', length: 0, nullable: true)]
    private $crewImage;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'crew_Type', type: 'string', length: 45, nullable: true)]
    private $crewType;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'crew_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $crewCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'crew_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $crewLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'crew_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $crewLastModifier;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'crew_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $crewOwner;

    public function getCrewId(): ?int
    {
        return $this->crewId;
    }

    public function getCrewFirstName(): ?string
    {
        return $this->crewFirstName;
    }

    public function setCrewFirstName(string $crewFirstName): static
    {
        $this->crewFirstName = $crewFirstName;

        return $this;
    }

    public function getCrewMiddleName(): ?string
    {
        return $this->crewMiddleName;
    }

    public function setCrewMiddleName(?string $crewMiddleName): static
    {
        $this->crewMiddleName = $crewMiddleName;

        return $this;
    }

    public function getCrewLastName(): ?string
    {
        return $this->crewLastName;
    }

    public function setCrewLastName(?string $crewLastName): static
    {
        $this->crewLastName = $crewLastName;

        return $this;
    }

    public function getCrewGender(): ?string
    {
        return $this->crewGender;
    }

    public function setCrewGender(?string $crewGender): static
    {
        $this->crewGender = $crewGender;

        return $this;
    }

    public function getCrewBirthdate(): ?\DateTime
    {
        return $this->crewBirthdate;
    }

    public function setCrewBirthdate(?\DateTime $crewBirthdate): static
    {
        $this->crewBirthdate = $crewBirthdate;

        return $this;
    }

    public function getCrewDeathdate(): ?\DateTime
    {
        return $this->crewDeathdate;
    }

    public function setCrewDeathdate(?\DateTime $crewDeathdate): static
    {
        $this->crewDeathdate = $crewDeathdate;

        return $this;
    }

    public function getCrewImage(): ?string
    {
        return $this->crewImage;
    }

    public function setCrewImage(?string $crewImage): static
    {
        $this->crewImage = $crewImage;

        return $this;
    }

    public function getCrewType(): ?string
    {
        return $this->crewType;
    }

    public function setCrewType(?string $crewType): static
    {
        $this->crewType = $crewType;

        return $this;
    }

    public function getCrewCreatedAt(): ?\DateTime
    {
        return $this->crewCreatedAt;
    }

    public function setCrewCreatedAt(\DateTime $crewCreatedAt): static
    {
        $this->crewCreatedAt = $crewCreatedAt;

        return $this;
    }

    public function getCrewLastModifiedAt(): ?\DateTime
    {
        return $this->crewLastModifiedAt;
    }

    public function setCrewLastModifiedAt(\DateTime $crewLastModifiedAt): static
    {
        $this->crewLastModifiedAt = $crewLastModifiedAt;

        return $this;
    }

    public function getCrewLastModifier(): ?ManagementUsers
    {
        return $this->crewLastModifier;
    }

    public function setCrewLastModifier(?ManagementUsers $crewLastModifier): static
    {
        $this->crewLastModifier = $crewLastModifier;

        return $this;
    }

    public function getCrewOwner(): ?ManagementUsers
    {
        return $this->crewOwner;
    }

    public function setCrewOwner(?ManagementUsers $crewOwner): static
    {
        $this->crewOwner = $crewOwner;

        return $this;
    }


}
