<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * V2ApiTemplates
 */
#[ORM\Table(name: 'V2__Api_Templates')]
#[ORM\Index(name: 'apiA_Creator', columns: ['apiT_Owner_Id'])]
#[ORM\Index(name: 'apia_Last_modifier', columns: ['apiT_Last_modifier'])]
#[ORM\Entity]
class V2ApiTemplates
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'Template_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $templateId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Template_Content', type: 'text', length: 0, nullable: false)]
    private $templateContent;

    /**
     * @var int
     */
    #[ORM\Column(name: 'apiT_Language_Id', type: 'integer', nullable: false)]
    private $apitLanguageId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apiT_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $apitCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'apit_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $apitLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'apiT_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $apitOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'apiT_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $apitLastModifier;

    public function getTemplateId(): ?int
    {
        return $this->templateId;
    }

    public function getTemplateContent(): ?string
    {
        return $this->templateContent;
    }

    public function setTemplateContent(string $templateContent): static
    {
        $this->templateContent = $templateContent;

        return $this;
    }

    public function getApitLanguageId(): ?int
    {
        return $this->apitLanguageId;
    }

    public function setApitLanguageId(int $apitLanguageId): static
    {
        $this->apitLanguageId = $apitLanguageId;

        return $this;
    }

    public function getApitCreatedAt(): ?\DateTime
    {
        return $this->apitCreatedAt;
    }

    public function setApitCreatedAt(\DateTime $apitCreatedAt): static
    {
        $this->apitCreatedAt = $apitCreatedAt;

        return $this;
    }

    public function getApitLastModifiedAt(): ?\DateTime
    {
        return $this->apitLastModifiedAt;
    }

    public function setApitLastModifiedAt(\DateTime $apitLastModifiedAt): static
    {
        $this->apitLastModifiedAt = $apitLastModifiedAt;

        return $this;
    }

    public function getApitOwner(): ?ManagementUsers
    {
        return $this->apitOwner;
    }

    public function setApitOwner(?ManagementUsers $apitOwner): static
    {
        $this->apitOwner = $apitOwner;

        return $this;
    }

    public function getApitLastModifier(): ?ManagementUsers
    {
        return $this->apitLastModifier;
    }

    public function setApitLastModifier(?ManagementUsers $apitLastModifier): static
    {
        $this->apitLastModifier = $apitLastModifier;

        return $this;
    }


}
