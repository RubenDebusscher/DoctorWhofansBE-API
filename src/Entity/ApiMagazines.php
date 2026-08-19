<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiMagazines
 */
#[ORM\Table(name: 'api__magazines')]
#[ORM\Index(name: 'magazine_Page', columns: ['Page_Id'])]
#[ORM\Index(name: 'magazine_Owner_Id', columns: ['magazine_Owner_Id'])]
#[ORM\Index(name: 'magazine_Last_Modifier', columns: ['magazine_Last_modifier'])]
#[ORM\Entity]
class ApiMagazines
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'magazine_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $magazineId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'magazine_Issue', type: 'text', length: 65535, nullable: false)]
    private $magazineIssue;

    /**
     * @var string
     */
    #[ORM\Column(name: 'magazine_Type', type: 'text', length: 65535, nullable: false)]
    private $magazineType;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'magazine_CoverDate', type: 'date', nullable: false)]
    private $magazineCoverdate;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'magazine_ReleaseDate', type: 'date', nullable: false)]
    private $magazineReleasedate;

    /**
     * @var string
     */
    #[ORM\Column(name: 'magazine_Format', type: 'text', length: 65535, nullable: false)]
    private $magazineFormat;

    /**
     * @var string
     */
    #[ORM\Column(name: 'magazine_Editor', type: 'text', length: 65535, nullable: false)]
    private $magazineEditor;

    /**
     * @var string
     */
    #[ORM\Column(name: 'magazine_Publisher', type: 'text', length: 65535, nullable: false)]
    private $magazinePublisher;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'magazine_PrevIssue', type: 'integer', nullable: true)]
    private $magazinePrevissue;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'magazine_NextIssue', type: 'integer', nullable: true)]
    private $magazineNextissue;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'magazine_Image', type: 'text', length: 65535, nullable: true)]
    private $magazineImage;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'magazine_Image_mimetype', type: 'text', length: 65535, nullable: true, options: ['default' => 'jpeg'])]
    private $magazineImageMimetype = 'jpeg';

    /**
     * @var int
     */
    #[ORM\Column(name: 'magazine_Owner_Id', type: 'integer', nullable: false)]
    private $magazineOwnerId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'magazine_Last_modifier', type: 'integer', nullable: false)]
    private $magazineLastModifier;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'magazine_Created_At', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $magazineCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'magazine_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $magazineLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementPages
     */
    #[ORM\JoinColumn(name: 'Page_Id', referencedColumnName: 'page_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementPages::class)]
    private $page;

    public function getMagazineId(): ?int
    {
        return $this->magazineId;
    }

    public function getMagazineIssue(): ?string
    {
        return $this->magazineIssue;
    }

    public function setMagazineIssue(string $magazineIssue): static
    {
        $this->magazineIssue = $magazineIssue;

        return $this;
    }

    public function getMagazineType(): ?string
    {
        return $this->magazineType;
    }

    public function setMagazineType(string $magazineType): static
    {
        $this->magazineType = $magazineType;

        return $this;
    }

    public function getMagazineCoverdate(): ?\DateTime
    {
        return $this->magazineCoverdate;
    }

    public function setMagazineCoverdate(\DateTime $magazineCoverdate): static
    {
        $this->magazineCoverdate = $magazineCoverdate;

        return $this;
    }

    public function getMagazineReleasedate(): ?\DateTime
    {
        return $this->magazineReleasedate;
    }

    public function setMagazineReleasedate(\DateTime $magazineReleasedate): static
    {
        $this->magazineReleasedate = $magazineReleasedate;

        return $this;
    }

    public function getMagazineFormat(): ?string
    {
        return $this->magazineFormat;
    }

    public function setMagazineFormat(string $magazineFormat): static
    {
        $this->magazineFormat = $magazineFormat;

        return $this;
    }

    public function getMagazineEditor(): ?string
    {
        return $this->magazineEditor;
    }

    public function setMagazineEditor(string $magazineEditor): static
    {
        $this->magazineEditor = $magazineEditor;

        return $this;
    }

    public function getMagazinePublisher(): ?string
    {
        return $this->magazinePublisher;
    }

    public function setMagazinePublisher(string $magazinePublisher): static
    {
        $this->magazinePublisher = $magazinePublisher;

        return $this;
    }

    public function getMagazinePrevissue(): ?int
    {
        return $this->magazinePrevissue;
    }

    public function setMagazinePrevissue(?int $magazinePrevissue): static
    {
        $this->magazinePrevissue = $magazinePrevissue;

        return $this;
    }

    public function getMagazineNextissue(): ?int
    {
        return $this->magazineNextissue;
    }

    public function setMagazineNextissue(?int $magazineNextissue): static
    {
        $this->magazineNextissue = $magazineNextissue;

        return $this;
    }

    public function getMagazineImage(): ?string
    {
        return $this->magazineImage;
    }

    public function setMagazineImage(?string $magazineImage): static
    {
        $this->magazineImage = $magazineImage;

        return $this;
    }

    public function getMagazineImageMimetype(): ?string
    {
        return $this->magazineImageMimetype;
    }

    public function setMagazineImageMimetype(?string $magazineImageMimetype): static
    {
        $this->magazineImageMimetype = $magazineImageMimetype;

        return $this;
    }

    public function getMagazineOwnerId(): ?int
    {
        return $this->magazineOwnerId;
    }

    public function setMagazineOwnerId(int $magazineOwnerId): static
    {
        $this->magazineOwnerId = $magazineOwnerId;

        return $this;
    }

    public function getMagazineLastModifier(): ?int
    {
        return $this->magazineLastModifier;
    }

    public function setMagazineLastModifier(int $magazineLastModifier): static
    {
        $this->magazineLastModifier = $magazineLastModifier;

        return $this;
    }

    public function getMagazineCreatedAt(): ?\DateTime
    {
        return $this->magazineCreatedAt;
    }

    public function setMagazineCreatedAt(\DateTime $magazineCreatedAt): static
    {
        $this->magazineCreatedAt = $magazineCreatedAt;

        return $this;
    }

    public function getMagazineLastModifiedAt(): ?\DateTime
    {
        return $this->magazineLastModifiedAt;
    }

    public function setMagazineLastModifiedAt(\DateTime $magazineLastModifiedAt): static
    {
        $this->magazineLastModifiedAt = $magazineLastModifiedAt;

        return $this;
    }

    public function getPage(): ?ManagementPages
    {
        return $this->page;
    }

    public function setPage(?ManagementPages $page): static
    {
        $this->page = $page;

        return $this;
    }


}
