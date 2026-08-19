<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContentGallery
 */
#[ORM\Table(name: 'content__gallery')]
#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/content-galleries'),
        new Get(uriTemplate: '/content-galleries/{id}'),
    ]
)]
class ContentGallery
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'CG_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $cgId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'CG_Name', type: 'text', length: 65535, nullable: false)]
    private $cgName;

    /**
     * @var int
     */
    #[ORM\Column(name: 'CG_Page', type: 'integer', nullable: false)]
    private $cgPage;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'Gallery_Created_At', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $galleryCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'Gallery_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => '0000-00-00 00:00:00'])]
    private $galleryLastModifiedAt = '0000-00-00 00:00:00';

    /**
     * @var int
     */
    #[ORM\Column(name: 'Gallery_Last_modifier', type: 'integer', nullable: false)]
    private $galleryLastModifier;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'Gallery_Event', type: 'integer', nullable: true)]
    private $galleryEvent;

    /**
     * @var int
     */
    #[ORM\Column(name: 'Gallery_Owner_Id', type: 'integer', nullable: false)]
    private $galleryOwnerId;

    public function getCgId(): ?int
    {
        return $this->cgId;
    }

    public function getCgName(): ?string
    {
        return $this->cgName;
    }

    public function setCgName(string $cgName): static
    {
        $this->cgName = $cgName;

        return $this;
    }

    public function getCgPage(): ?int
    {
        return $this->cgPage;
    }

    public function setCgPage(int $cgPage): static
    {
        $this->cgPage = $cgPage;

        return $this;
    }

    public function getGalleryCreatedAt(): ?\DateTime
    {
        return $this->galleryCreatedAt;
    }

    public function setGalleryCreatedAt(\DateTime $galleryCreatedAt): static
    {
        $this->galleryCreatedAt = $galleryCreatedAt;

        return $this;
    }

    public function getGalleryLastModifiedAt(): ?\DateTime
    {
        return $this->galleryLastModifiedAt;
    }

    public function setGalleryLastModifiedAt(\DateTime $galleryLastModifiedAt): static
    {
        $this->galleryLastModifiedAt = $galleryLastModifiedAt;

        return $this;
    }

    public function getGalleryLastModifier(): ?int
    {
        return $this->galleryLastModifier;
    }

    public function setGalleryLastModifier(int $galleryLastModifier): static
    {
        $this->galleryLastModifier = $galleryLastModifier;

        return $this;
    }

    public function getGalleryEvent(): ?int
    {
        return $this->galleryEvent;
    }

    public function setGalleryEvent(?int $galleryEvent): static
    {
        $this->galleryEvent = $galleryEvent;

        return $this;
    }

    public function getGalleryOwnerId(): ?int
    {
        return $this->galleryOwnerId;
    }

    public function setGalleryOwnerId(int $galleryOwnerId): static
    {
        $this->galleryOwnerId = $galleryOwnerId;

        return $this;
    }


}
