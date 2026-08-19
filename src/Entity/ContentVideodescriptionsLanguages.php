<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use App\Entity\ManagementLanguages; // <-- Deze import is essentieel!

/**
 * ContentVideodescriptionsLanguages
 */
#[ORM\Table(name: 'content__videodescriptions_languages')]
#[ORM\Index(name: 'DL_Last_Modifier_idx', columns: ['VL_Last_modifier'])]
#[ORM\Index(name: 'DL_Language_idx', columns: ['language_Id'])]
#[ORM\Index(name: 'VL_Item0_idx', columns: ['videodescription_Id'])]
#[ORM\Index(name: 'DL_Owner_idx', columns: ['VL_Owner_Id'])]
#[ORM\Entity]
class ContentVideodescriptionsLanguages
{
    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'VL_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $vlCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'VL_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $vlLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'VL_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $vlLastModifier;

    /**
     * @var \ContentVideodescriptions
     */
    #[ORM\JoinColumn(name: 'videodescription_Id', referencedColumnName: 'videodescription_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ContentVideodescriptions::class)]
    private $videodescription;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'VL_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $vlOwner;

    /**
     * @var \ManagementLanguages
     */
    #[ORM\ManyToOne(targetEntity: ManagementLanguages::class)]
    #[ORM\JoinColumn(name: 'language_id', referencedColumnName: 'language_Id')]
    private ?ManagementLanguages $language = null;

    public function getVlCreatedAt(): ?\DateTime
    {
        return $this->vlCreatedAt;
    }

    public function setVlCreatedAt(\DateTime $vlCreatedAt): static
    {
        $this->vlCreatedAt = $vlCreatedAt;

        return $this;
    }

    public function getVlLastModifiedAt(): ?\DateTime
    {
        return $this->vlLastModifiedAt;
    }

    public function setVlLastModifiedAt(\DateTime $vlLastModifiedAt): static
    {
        $this->vlLastModifiedAt = $vlLastModifiedAt;

        return $this;
    }

    public function getVlLastModifier(): ?ManagementUsers
    {
        return $this->vlLastModifier;
    }

    public function setVlLastModifier(?ManagementUsers $vlLastModifier): static
    {
        $this->vlLastModifier = $vlLastModifier;

        return $this;
    }

    public function getVideodescription(): ?ContentVideodescriptions
    {
        return $this->videodescription;
    }

    public function setVideodescription(ContentVideodescriptions $videodescription): static
    {
        $this->videodescription = $videodescription;

        return $this;
    }

    public function getVlOwner(): ?ManagementUsers
    {
        return $this->vlOwner;
    }

    public function setVlOwner(?ManagementUsers $vlOwner): static
    {
        $this->vlOwner = $vlOwner;

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
