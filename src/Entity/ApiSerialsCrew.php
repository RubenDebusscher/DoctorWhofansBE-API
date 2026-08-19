<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ApiSerialsCrew
 */
#[ORM\Table(name: 'api__serials_crew')]
#[ORM\Index(name: 'API__serials_writers__Owner__ID', columns: ['serials_crew_Owner_Id'])]
#[ORM\Index(name: 'API__serials_writers__Last__Modified__User__ID', columns: ['serials_crew_Last_modifier'])]
#[ORM\Index(name: 'api__serials_crew_ibfk_60_idx', columns: ['SC_Crew_Id'])]
#[ORM\Index(name: 'IDX_36672401697B8E82', columns: ['SC_Serial_Id'])]
#[ORM\Entity]
class ApiSerialsCrew
{
    /**
     * @var string|null
     */
    #[ORM\Column(name: 'SC_Type', type: 'string', length: 45, nullable: true)]
    private $scType;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serials_crew_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $serialsCrewCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serials_crew_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $serialsCrewLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'serials_crew_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $serialsCrewOwner;

    /**
     * @var \ApiCrew
     */
    #[ORM\JoinColumn(name: 'SC_Crew_Id', referencedColumnName: 'crew_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ApiCrew::class)]
    private $scCrew;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'serials_crew_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $serialsCrewLastModifier;

    /**
     * @var \ApiSerials
     */
    #[ORM\JoinColumn(name: 'SC_Serial_Id', referencedColumnName: 'serial_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ApiSerials::class)]
    private $scSerial;

    public function getScType(): ?string
    {
        return $this->scType;
    }

    public function setScType(?string $scType): static
    {
        $this->scType = $scType;

        return $this;
    }

    public function getSerialsCrewCreatedAt(): ?\DateTime
    {
        return $this->serialsCrewCreatedAt;
    }

    public function setSerialsCrewCreatedAt(\DateTime $serialsCrewCreatedAt): static
    {
        $this->serialsCrewCreatedAt = $serialsCrewCreatedAt;

        return $this;
    }

    public function getSerialsCrewLastModifiedAt(): ?\DateTime
    {
        return $this->serialsCrewLastModifiedAt;
    }

    public function setSerialsCrewLastModifiedAt(\DateTime $serialsCrewLastModifiedAt): static
    {
        $this->serialsCrewLastModifiedAt = $serialsCrewLastModifiedAt;

        return $this;
    }

    public function getSerialsCrewOwner(): ?ManagementUsers
    {
        return $this->serialsCrewOwner;
    }

    public function setSerialsCrewOwner(?ManagementUsers $serialsCrewOwner): static
    {
        $this->serialsCrewOwner = $serialsCrewOwner;

        return $this;
    }

    public function getScCrew(): ?ApiCrew
    {
        return $this->scCrew;
    }

    public function setScCrew(ApiCrew $scCrew): static
    {
        $this->scCrew = $scCrew;

        return $this;
    }

    public function getSerialsCrewLastModifier(): ?ManagementUsers
    {
        return $this->serialsCrewLastModifier;
    }

    public function setSerialsCrewLastModifier(?ManagementUsers $serialsCrewLastModifier): static
    {
        $this->serialsCrewLastModifier = $serialsCrewLastModifier;

        return $this;
    }

    public function getScSerial(): ?ApiSerials
    {
        return $this->scSerial;
    }

    public function setScSerial(ApiSerials $scSerial): static
    {
        $this->scSerial = $scSerial;

        return $this;
    }


}
