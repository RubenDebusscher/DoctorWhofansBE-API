<?php

namespace App\Entity;

use App\Entity\ManagementCategories;
use App\Entity\ManagementPages;
use App\Entity\ManagementUsers;
use Doctrine\ORM\Mapping as ORM;

/**
 * ManagementPagesCategories
 */
#[ORM\Table(name: 'management__pages_categories')]
#[ORM\Index(name: 'PC_Page_idx', columns: ['PC_page_Id'])]
#[ORM\Index(name: 'PC_Category_idx', columns: ['PC_category_Id'])]
#[ORM\Index(name: 'PC_Owner_Id_idx', columns: ['PC_Owner_Id'])]
#[ORM\Index(name: 'PC_Last_Modifier_idx', columns: ['PC_Last_modifier'])]
#[ORM\Entity]
class ManagementPagesCategories
{
    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'PC_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $pcCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'PC_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $pcLastModifiedAt = null;

    /**
     * @var ManagementCategories
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\ManyToOne(targetEntity: ManagementCategories::class)]
    #[ORM\JoinColumn(name: 'PC_category_Id', referencedColumnName: 'category_Id', nullable: false)]
    private $pcCategory;

    /**
     * @var ManagementPages
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\ManyToOne(targetEntity: ManagementPages::class, inversedBy: 'pageCategories')]
    #[ORM\JoinColumn(name: 'PC_page_Id', referencedColumnName: 'page_Id', nullable: false)]
    private $pcPage;

    /**
     * @var ManagementUsers|null
     */
    #[ORM\JoinColumn(name: 'PC_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    private $pcLastModifier;

    /**
     * @var ManagementUsers|null
     */
    #[ORM\JoinColumn(name: 'PC_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    private $pcOwner;

    public function getPcCreatedAt(): ?\DateTime
    {
        return $this->pcCreatedAt;
    }

    public function setPcCreatedAt(\DateTime $pcCreatedAt): static
    {
        $this->pcCreatedAt = $pcCreatedAt;

        return $this;
    }

    public function getPcLastModifiedAt(): ?\DateTime
    {
        return $this->pcLastModifiedAt;
    }

    public function setPcLastModifiedAt(\DateTime $pcLastModifiedAt): static
    {
        $this->pcLastModifiedAt = $pcLastModifiedAt;

        return $this;
    }

    public function getPcCategory(): ?ManagementCategories
    {
        return $this->pcCategory;
    }

    public function setPcCategory(?ManagementCategories $pcCategory): static
    {
        $this->pcCategory = $pcCategory;

        return $this;
    }

    public function getPcPage(): ?ManagementPages
    {
        return $this->pcPage;
    }

    public function setPcPage(?ManagementPages $pcPage): static
    {
        $this->pcPage = $pcPage;

        return $this;
    }

    public function getPcLastModifier(): ?ManagementUsers
    {
        return $this->pcLastModifier;
    }

    public function setPcLastModifier(?ManagementUsers $pcLastModifier): static
    {
        $this->pcLastModifier = $pcLastModifier;

        return $this;
    }

    public function getPcOwner(): ?ManagementUsers
    {
        return $this->pcOwner;
    }

    public function setPcOwner(?ManagementUsers $pcOwner): static
    {
        $this->pcOwner = $pcOwner;

        return $this;
    }
}
