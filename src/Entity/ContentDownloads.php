<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ContentDownloads
 */
#[ORM\Table(name: 'content__downloads')]
#[ORM\Index(name: 'download_Owner_idx', columns: ['download_Owner_Id'])]
#[ORM\Index(name: 'download_Last_modifier_idx', columns: ['download_Last_modifier'])]
#[ORM\Index(name: 'pagina', columns: ['download_Page'])]
#[ORM\Entity]
class ContentDownloads
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'download_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $downloadId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'download_Type', type: 'string', length: 50, nullable: false)]
    private $downloadType;

    /**
     * @var string
     */
    #[ORM\Column(name: 'download_Name', type: 'string', length: 255, nullable: false)]
    private $downloadName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'download_File', type: 'string', length: 900, nullable: true)]
    private $downloadFile;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'download_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $downloadCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'download_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $downloadLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementPages
     */
    #[ORM\JoinColumn(name: 'download_Page', referencedColumnName: 'page_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementPages::class)]
    private $downloadPage;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'download_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $downloadLastModifier;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'download_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $downloadOwner;

    public function getDownloadId(): ?int
    {
        return $this->downloadId;
    }

    public function getDownloadType(): ?string
    {
        return $this->downloadType;
    }

    public function setDownloadType(string $downloadType): static
    {
        $this->downloadType = $downloadType;

        return $this;
    }

    public function getDownloadName(): ?string
    {
        return $this->downloadName;
    }

    public function setDownloadName(string $downloadName): static
    {
        $this->downloadName = $downloadName;

        return $this;
    }

    public function getDownloadFile(): ?string
    {
        return $this->downloadFile;
    }

    public function setDownloadFile(?string $downloadFile): static
    {
        $this->downloadFile = $downloadFile;

        return $this;
    }

    public function getDownloadCreatedAt(): ?\DateTime
    {
        return $this->downloadCreatedAt;
    }

    public function setDownloadCreatedAt(\DateTime $downloadCreatedAt): static
    {
        $this->downloadCreatedAt = $downloadCreatedAt;

        return $this;
    }

    public function getDownloadLastModifiedAt(): ?\DateTime
    {
        return $this->downloadLastModifiedAt;
    }

    public function setDownloadLastModifiedAt(\DateTime $downloadLastModifiedAt): static
    {
        $this->downloadLastModifiedAt = $downloadLastModifiedAt;

        return $this;
    }

    public function getDownloadPage(): ?ManagementPages
    {
        return $this->downloadPage;
    }

    public function setDownloadPage(?ManagementPages $downloadPage): static
    {
        $this->downloadPage = $downloadPage;

        return $this;
    }

    public function getDownloadLastModifier(): ?ManagementUsers
    {
        return $this->downloadLastModifier;
    }

    public function setDownloadLastModifier(?ManagementUsers $downloadLastModifier): static
    {
        $this->downloadLastModifier = $downloadLastModifier;

        return $this;
    }

    public function getDownloadOwner(): ?ManagementUsers
    {
        return $this->downloadOwner;
    }

    public function setDownloadOwner(?ManagementUsers $downloadOwner): static
    {
        $this->downloadOwner = $downloadOwner;

        return $this;
    }


}
