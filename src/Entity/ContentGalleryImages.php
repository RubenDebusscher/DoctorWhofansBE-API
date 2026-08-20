<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContentGalleryImages
 */
#[ORM\Table(name: 'content__gallery__images')]
#[ORM\Index(name: 'image_Owner_idx', columns: ['image_Owner_Id'])]
#[ORM\Index(name: 'image_last_modifier_idx', columns: ['image_Last_modifier'])]
#[ORM\Index(name: 'Gallery_Id', columns: ['Gallery_Id'])]
#[ORM\Entity]
class ContentGalleryImages
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
     * @var string
     */
    #[ORM\Column(name: 'image_Folder', type: 'text', length: 65535, nullable: false)]
    private $imageFolder;

    /**
     * @var string
     */
    #[ORM\Column(name: 'image_Caption', type: 'text', length: 65535, nullable: false)]
    private $imageCaption;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'image_active', type: 'boolean', nullable: true)]
    private $imageActive;

    /**
     * @var float
     */
    #[ORM\Column(name: 'Image_Order', type: 'float', precision: 10, scale: 0, nullable: false)]
    private $imageOrder;

    /**
     * @var int
     */
    #[ORM\Column(name: 'image_Owner_Id', type: 'integer', nullable: false)]
    private $imageOwnerId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'image_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $imageCreatedAt = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'image_Last_modifier', type: 'integer', nullable: false)]
    private $imageLastModifier;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'image_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $imageLastModifiedAt = null;

    /**
     * @var \ContentGallery
     */
    #[ORM\JoinColumn(name: 'Gallery_Id', referencedColumnName: 'CG_Id')]
    #[ORM\ManyToOne(targetEntity: \ContentGallery::class)]
    private $gallery;

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

    public function getImageFolder(): ?string
    {
        return $this->imageFolder;
    }

    public function setImageFolder(string $imageFolder): static
    {
        $this->imageFolder = $imageFolder;

        return $this;
    }

    public function getImageCaption(): ?string
    {
        return $this->imageCaption;
    }

    public function setImageCaption(string $imageCaption): static
    {
        $this->imageCaption = $imageCaption;

        return $this;
    }

    public function isImageActive(): ?bool
    {
        return $this->imageActive;
    }

    public function setImageActive(?bool $imageActive): static
    {
        $this->imageActive = $imageActive;

        return $this;
    }

    public function getImageOrder(): ?float
    {
        return $this->imageOrder;
    }

    public function setImageOrder(float $imageOrder): static
    {
        $this->imageOrder = $imageOrder;

        return $this;
    }

    public function getImageOwnerId(): ?int
    {
        return $this->imageOwnerId;
    }

    public function setImageOwnerId(int $imageOwnerId): static
    {
        $this->imageOwnerId = $imageOwnerId;

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

    public function getImageLastModifier(): ?int
    {
        return $this->imageLastModifier;
    }

    public function setImageLastModifier(int $imageLastModifier): static
    {
        $this->imageLastModifier = $imageLastModifier;

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

    public function getGallery(): ?ContentGallery
    {
        return $this->gallery;
    }

    public function setGallery(?ContentGallery $gallery): static
    {
        $this->gallery = $gallery;

        return $this;
    }


}
