<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ManagementImages
 */
#[ORM\Table(name: 'management__images')]
#[ORM\Index(name: 'image_last_modifier_idx', columns: ['image_Last_modifier'])]
#[ORM\Index(name: 'image_Owner_idx', columns: ['image_Owner_Id'])]
#[ORM\Entity]
class ManagementImages
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'image_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $imageId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'image_File', type: 'text', length: 0, nullable: false, options: ['comment' => 'filenaam, de file wordt door Xataface geupload naar uploads/afbeeldingen==> deze tabel dient voornamelijk om admins een catalogus van de afbeeldingen te geven en toe te laten deze te uploaden zonder dat FTP toegang nodig is.'])]
    private $imageFile;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'image_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $imageCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'image_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $imageLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'image_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $imageOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'image_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $imageLastModifier;

    public function getImageId(): ?int
    {
        return $this->imageId;
    }

    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }

    public function setImageFile(string $imageFile): static
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getImageCreatedAt(): ?\DateTime
    {
        return $this->imageCreatedAt;
    }

    public function setImageCreatedAt(\DateTime $imageCreatedAt): static
    {
        $this->imageCreatedAt = $imageCreatedAt;

        return $this;
    }

    public function getImageLastModifiedAt(): ?\DateTime
    {
        return $this->imageLastModifiedAt;
    }

    public function setImageLastModifiedAt(\DateTime $imageLastModifiedAt): static
    {
        $this->imageLastModifiedAt = $imageLastModifiedAt;

        return $this;
    }

    public function getImageOwner(): ?ManagementUsers
    {
        return $this->imageOwner;
    }

    public function setImageOwner(?ManagementUsers $imageOwner): static
    {
        $this->imageOwner = $imageOwner;

        return $this;
    }

    public function getImageLastModifier(): ?ManagementUsers
    {
        return $this->imageLastModifier;
    }

    public function setImageLastModifier(?ManagementUsers $imageLastModifier): static
    {
        $this->imageLastModifier = $imageLastModifier;

        return $this;
    }


}
