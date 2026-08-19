<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ManagementCategories
 */
#[ORM\Table(name: 'management__categories')]
#[ORM\Index(name: 'Category_Last_Modifier_idx', columns: ['category_Last_modifier'])]
#[ORM\Index(name: 'Category_Owner_idx', columns: ['category_Owner_Id'])]
#[ORM\Entity]
class ManagementCategories
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'category_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $categoryId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'category_Name', type: 'string', length: 500, nullable: false)]
    private $categoryName;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'category_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $categoryCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'category_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $categoryLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'category_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $categoryOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'category_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $categoryLastModifier;

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): static
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    public function getCategoryCreatedAt(): ?\DateTime
    {
        return $this->categoryCreatedAt;
    }

    public function setCategoryCreatedAt(\DateTime $categoryCreatedAt): static
    {
        $this->categoryCreatedAt = $categoryCreatedAt;

        return $this;
    }

    public function getCategoryLastModifiedAt(): ?\DateTime
    {
        return $this->categoryLastModifiedAt;
    }

    public function setCategoryLastModifiedAt(\DateTime $categoryLastModifiedAt): static
    {
        $this->categoryLastModifiedAt = $categoryLastModifiedAt;

        return $this;
    }

    public function getCategoryOwner(): ?ManagementUsers
    {
        return $this->categoryOwner;
    }

    public function setCategoryOwner(?ManagementUsers $categoryOwner): static
    {
        $this->categoryOwner = $categoryOwner;

        return $this;
    }

    public function getCategoryLastModifier(): ?ManagementUsers
    {
        return $this->categoryLastModifier;
    }

    public function setCategoryLastModifier(?ManagementUsers $categoryLastModifier): static
    {
        $this->categoryLastModifier = $categoryLastModifier;

        return $this;
    }


}
