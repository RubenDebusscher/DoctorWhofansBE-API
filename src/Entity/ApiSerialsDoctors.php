<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ApiSerialsDoctors
 */
#[ORM\Table(name: 'api__serials_doctors')]
#[ORM\Index(name: 'API__serials_doctors__Last__Modified__User__ID', columns: ['serials_doctors_Last_modifier'])]
#[ORM\Index(name: 'api__serials_doctors_ibfk_2', columns: ['DF_Serial_Id'])]
#[ORM\Index(name: 'doctor_id', columns: ['DF_Doctor_Id'])]
#[ORM\Index(name: 'API__serials_doctors__Owner__ID', columns: ['serials_doctors_Owner_Id'])]
#[ORM\Entity]
class ApiSerialsDoctors
{
    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serials_doctors_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $serialsDoctorsCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'serials_doctors_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $serialsDoctorsLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'serials_doctors_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $serialsDoctorsOwner;

    /**
     * @var \ApiSerials
     */
    #[ORM\JoinColumn(name: 'DF_Serial_Id', referencedColumnName: 'serial_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ApiSerials::class)]
    private $dfSerial;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'serials_doctors_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $serialsDoctorsLastModifier;

    /**
     * @var \ApiDoctors
     */
    #[ORM\JoinColumn(name: 'DF_Doctor_Id', referencedColumnName: 'doctor_id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ApiDoctors::class)]
    private $dfDoctor;

    public function getSerialsDoctorsCreatedAt(): ?\DateTime
    {
        return $this->serialsDoctorsCreatedAt;
    }

    public function setSerialsDoctorsCreatedAt(\DateTime $serialsDoctorsCreatedAt): static
    {
        $this->serialsDoctorsCreatedAt = $serialsDoctorsCreatedAt;

        return $this;
    }

    public function getSerialsDoctorsLastModifiedAt(): ?\DateTime
    {
        return $this->serialsDoctorsLastModifiedAt;
    }

    public function setSerialsDoctorsLastModifiedAt(\DateTime $serialsDoctorsLastModifiedAt): static
    {
        $this->serialsDoctorsLastModifiedAt = $serialsDoctorsLastModifiedAt;

        return $this;
    }

    public function getSerialsDoctorsOwner(): ?ManagementUsers
    {
        return $this->serialsDoctorsOwner;
    }

    public function setSerialsDoctorsOwner(?ManagementUsers $serialsDoctorsOwner): static
    {
        $this->serialsDoctorsOwner = $serialsDoctorsOwner;

        return $this;
    }

    public function getDfSerial(): ?ApiSerials
    {
        return $this->dfSerial;
    }

    public function setDfSerial(ApiSerials $dfSerial): static
    {
        $this->dfSerial = $dfSerial;

        return $this;
    }

    public function getSerialsDoctorsLastModifier(): ?ManagementUsers
    {
        return $this->serialsDoctorsLastModifier;
    }

    public function setSerialsDoctorsLastModifier(?ManagementUsers $serialsDoctorsLastModifier): static
    {
        $this->serialsDoctorsLastModifier = $serialsDoctorsLastModifier;

        return $this;
    }

    public function getDfDoctor(): ?ApiDoctors
    {
        return $this->dfDoctor;
    }

    public function setDfDoctor(ApiDoctors $dfDoctor): static
    {
        $this->dfDoctor = $dfDoctor;

        return $this;
    }


}
