<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use App\Entity\ManagementLanguages; // <-- Deze import is essentieel!

/**
 * ContentDownloadsLanguages
 */
#[ORM\Table(name: 'content__downloads_languages')]
#[ORM\Index(name: 'DL_Last_Modifier_idx', columns: ['DL_Last_modifier'])]
#[ORM\Index(name: 'Download_idx', columns: ['DL_download_Id'])]
#[ORM\Index(name: 'DL_Language_idx', columns: ['DL_language_Id'])]
#[ORM\Index(name: 'DL_Owner_idx', columns: ['DL_Owner_Id'])]
#[ORM\Entity]
class ContentDownloadsLanguages
{
    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'DL_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $dlCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'DL_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $dlLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'DL_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $dlLastModifier;

    /**
     * @var \ContentDownloads
     */
    #[ORM\JoinColumn(name: 'DL_download_Id', referencedColumnName: 'download_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ContentDownloads::class)]
    private $dlDownload;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'DL_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $dlOwner;

    /**
     * @var \ManagementLanguages
     */
    #[ORM\ManyToOne(targetEntity: ManagementLanguages::class)]
    #[ORM\JoinColumn(name: 'DL_language_id', referencedColumnName: 'language_Id')] // <-- referencedColumnName aanpassen
    private ?ManagementLanguages $language = null;

    public function getDlCreatedAt(): ?\DateTime
    {
        return $this->dlCreatedAt;
    }

    public function setDlCreatedAt(\DateTime $dlCreatedAt): static
    {
        $this->dlCreatedAt = $dlCreatedAt;

        return $this;
    }

    public function getDlLastModifiedAt(): ?\DateTime
    {
        return $this->dlLastModifiedAt;
    }

    public function setDlLastModifiedAt(\DateTime $dlLastModifiedAt): static
    {
        $this->dlLastModifiedAt = $dlLastModifiedAt;

        return $this;
    }

    public function getDlLastModifier(): ?ManagementUsers
    {
        return $this->dlLastModifier;
    }

    public function setDlLastModifier(?ManagementUsers $dlLastModifier): static
    {
        $this->dlLastModifier = $dlLastModifier;

        return $this;
    }

    public function getDlDownload(): ?ContentDownloads
    {
        return $this->dlDownload;
    }

    public function setDlDownload(ContentDownloads $dlDownload): static
    {
        $this->dlDownload = $dlDownload;

        return $this;
    }

    public function getDlOwner(): ?ManagementUsers
    {
        return $this->dlOwner;
    }

    public function setDlOwner(?ManagementUsers $dlOwner): static
    {
        $this->dlOwner = $dlOwner;

        return $this;
    }

    public function getLanguage(): ?ManagementLanguages
    {
        return $this->language;
    }

    public function setLanguage(?ManagementLanguages $language): static
    {
        $this->language = $language;

        return $this;
    }


}
