<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use App\Entity\ManagementLanguages; // <-- Deze import is essentieel!


/**
 * ContentItemsLanguages
 */
#[ORM\Table(name: 'content__items_languages')]
#[ORM\Index(name: 'DL_Last_Modifier_idx', columns: ['IL_Last_modifier'])]
#[ORM\Index(name: 'DL_Language_idx', columns: ['IL_language_Id'])]
#[ORM\Index(name: 'IL_Item_idx', columns: ['IL_item_Id'])]
#[ORM\Index(name: 'DL_Owner_idx', columns: ['IL_Owner_Id'])]
#[ORM\Entity]
class ContentItemsLanguages
{
    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'IL_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $ilCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'IL_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $ilLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'IL_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $ilLastModifier;

    /**
     * @var \ContentItems
     */
    #[ORM\JoinColumn(name: 'IL_item_Id', referencedColumnName: 'item_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ContentItems::class)]
    private $ilItem;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'IL_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $ilOwner;

    /**
     * @var \ManagementLanguages
     */
    #[ORM\JoinColumn(name: 'IL_language_Id', referencedColumnName: 'language_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: ManagementLanguages::class)]
    private ?ManagementLanguages $ilLanguage;

    public function getIlCreatedAt(): ?\DateTime
    {
        return $this->ilCreatedAt;
    }

    public function setIlCreatedAt(\DateTime $ilCreatedAt): static
    {
        $this->ilCreatedAt = $ilCreatedAt;

        return $this;
    }

    public function getIlLastModifiedAt(): ?\DateTime
    {
        return $this->ilLastModifiedAt;
    }

    public function setIlLastModifiedAt(\DateTime $ilLastModifiedAt): static
    {
        $this->ilLastModifiedAt = $ilLastModifiedAt;

        return $this;
    }

    public function getIlLastModifier(): ?ManagementUsers
    {
        return $this->ilLastModifier;
    }

    public function setIlLastModifier(?ManagementUsers $ilLastModifier): static
    {
        $this->ilLastModifier = $ilLastModifier;

        return $this;
    }

    public function getIlItem(): ?ContentItems
    {
        return $this->ilItem;
    }

    public function setIlItem(ContentItems $ilItem): static
    {
        $this->ilItem = $ilItem;

        return $this;
    }

    public function getIlOwner(): ?ManagementUsers
    {
        return $this->ilOwner;
    }

    public function setIlOwner(?ManagementUsers $ilOwner): static
    {
        $this->ilOwner = $ilOwner;

        return $this;
    }

    public function getIlLanguage(): ?ManagementLanguages
    {
        return $this->ilLanguage;
    }

    public function setIlLanguage(ManagementLanguages $ilLanguage): static
    {
        $this->ilLanguage = $ilLanguage;

        return $this;
    }


}
