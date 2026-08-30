<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiDoctors
 */
#[ORM\Table(name: 'api__doctors')]
#[ORM\Index(name: 'API__doctors__Last__Modified__User__ID', columns: ['doctor_Last_modifier'])]
#[ORM\Index(name: 'primary_actor', columns: ['doctor_Actor_Id'])]
#[ORM\Index(name: 'API__doctors__Owner__ID', columns: ['doctor_Owner_Id'])]
#[ORM\Entity]
class ApiDoctors
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'doctor_id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $doctorId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'doctor_Incarnation', type: 'text', length: 65535, nullable: false)]
    private $doctorIncarnation;

    /**
     * @var string
     */
    #[ORM\Column(name: 'doctor_order', type: 'decimal', precision: 19, scale: 4, nullable: false)]
    private $doctorOrder;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'doctor_Image', type: 'text', length: 0, nullable: true)]
    private $doctorImage;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'doctor_Page_Id', type: 'integer', nullable: true)]
    private $doctorPageId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'doctor_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $doctorCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'doctor_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $doctorLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'doctor_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $doctorOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'doctor_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $doctorLastModifier;

    /**
     * @var \ApiActors
     */
    #[ORM\JoinColumn(name: 'doctor_Actor_Id', referencedColumnName: 'actor_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiActors::class)]
    private $doctorActor;

    public function getDoctorId(): ?int
    {
        return $this->doctorId;
    }

    public function getDoctorIncarnation(): ?string
    {
        return $this->doctorIncarnation;
    }

    public function setDoctorIncarnation(string $doctorIncarnation): static
    {
        $this->doctorIncarnation = $doctorIncarnation;

        return $this;
    }

    public function getDoctorOrder(): ?string
    {
        return $this->doctorOrder;
    }

    public function setDoctorOrder(string $doctorOrder): static
    {
        $this->doctorOrder = $doctorOrder;

        return $this;
    }

    public function getDoctorImage(): ?string
    {
        return $this->doctorImage;
    }

    public function setDoctorImage(?string $doctorImage): static
    {
        $this->doctorImage = $doctorImage;

        return $this;
    }

    public function getDoctorPageId(): ?int
    {
        return $this->doctorPageId;
    }

    public function setDoctorPageId(?int $doctorPageId): static
    {
        $this->doctorPageId = $doctorPageId;

        return $this;
    }

    public function getDoctorCreatedAt(): ?\DateTime
    {
        return $this->doctorCreatedAt;
    }

    public function setDoctorCreatedAt(\DateTime $doctorCreatedAt): static
    {
        $this->doctorCreatedAt = $doctorCreatedAt;

        return $this;
    }

    public function getDoctorLastModifiedAt(): ?\DateTime
    {
        return $this->doctorLastModifiedAt;
    }

    public function setDoctorLastModifiedAt(\DateTime $doctorLastModifiedAt): static
    {
        $this->doctorLastModifiedAt = $doctorLastModifiedAt;

        return $this;
    }

    public function getDoctorOwner(): ?ManagementUsers
    {
        return $this->doctorOwner;
    }

    public function setDoctorOwner(?ManagementUsers $doctorOwner): static
    {
        $this->doctorOwner = $doctorOwner;

        return $this;
    }

    public function getDoctorLastModifier(): ?ManagementUsers
    {
        return $this->doctorLastModifier;
    }

    public function setDoctorLastModifier(?ManagementUsers $doctorLastModifier): static
    {
        $this->doctorLastModifier = $doctorLastModifier;

        return $this;
    }

    public function getDoctorActor(): ?ApiActors
    {
        return $this->doctorActor;
    }

    public function setDoctorActor(?ApiActors $doctorActor): static
    {
        $this->doctorActor = $doctorActor;

        return $this;
    }


}
