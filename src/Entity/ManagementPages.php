<?php

namespace App\Entity;

use App\Repository\ManagementPagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ManagementPagesRepository::class)]
#[ORM\Table(name: 'management__pages')]
#[ORM\HasLifecycleCallbacks]
class ManagementPages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'page_Id', type: Types::INTEGER)]
    #[Groups(['page:read', 'page:details'])]
    private ?int $id = null;

    #[ORM\Column(name: 'page_Name', type: Types::TEXT, nullable: true)]
    #[Groups(['page:read', 'page:details'])]
    private ?string $name = null;

    #[ORM\Column(name: 'page_Slug', type: Types::STRING, length: 255, nullable: true)]
    #[Groups(['page:read', 'page:details'])]
    private ?string $slug = null;

    #[ORM\Column(name: 'page_Link', type: Types::TEXT, nullable: true)]
    #[Groups(['page:read', 'page:details'])]
    private ?string $link = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'page_Parent_Id', referencedColumnName: 'page_Id', nullable: true, onDelete: 'SET NULL')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;


    /**
 * @var Collection<int, ManagementPagesCategories>
 */
    #[ORM\OneToMany(mappedBy: 'pcPage', targetEntity: ManagementPagesCategories::class)]
    private Collection $pageCategories;


    #[ORM\Column(name: 'page_Active', type: Types::SMALLINT, nullable: true, options: ['default' => 1])]
    #[Groups(['page:read', 'page:details'])]
    private ?int $active = 1;

    /**
     * Koppeling met de Code entiteit (Codegroep: PAGE_STATUS -> bijv. draft, published, archived)
     */
    #[ORM\ManyToOne(targetEntity: Code::class)]
    #[ORM\JoinColumn(name: 'page_Status_Code_Id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['page:read', 'page:details'])]
    private ?Code $status = null;

    #[ORM\Column(name: 'page_Order', type: Types::DECIMAL, precision: 19, scale: 4, nullable: true)]
    #[Groups(['page:read'])]
    private ?string $order = '0.0000';

    // --- DYNAMISCH BLOCK CANVAS & SETTINGS (2x JSON) ---

    #[ORM\Column(name: 'page_Blocks', type: Types::JSON, nullable: true)]
    #[Groups(['page:read', 'page:details'])]
    private ?array $blocks = [];

    #[ORM\Column(name: 'page_Settings', type: Types::JSON, nullable: true)]
    #[Groups(['page:read', 'page:details'])]
    private ?array $settings = [];

    // --- GEPLANDE PUBLICATIE / GELDIGHEID (2x DateTime) ---

    #[ORM\Column(name: 'page_Valid_From', type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['page:read', 'page:details'])]
    private ?\DateTimeInterface $validFrom = null;

    #[ORM\Column(name: 'page_Valid_To', type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['page:read', 'page:details'])]
    private ?\DateTimeInterface $validTo = null;

    /**
     * Koppeling met het API Item (V3Items)
     */
    #[ORM\ManyToOne(targetEntity: V3Items::class)]
    #[ORM\JoinColumn(name: 'page_API_Item', referencedColumnName: 'ItemID', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['page:details'])]
    private ?V3Items $apiItem = null;

    /**
     * 1 = Bewust geen API item gekoppeld (Systeempagina / Overzicht)
     * 0 = Moet gekoppeld worden of is gekoppeld
     */
    #[ORM\Column(name: 'page_Ignore_API', type: Types::BOOLEAN, options: ['default' => false])]
    #[Groups(['page:details', 'page:read'])]
    private bool $ignoreApi = false;

    // --- SEO METADATA ---

    #[ORM\Column(name: 'page_Meta_Title', type: Types::STRING, length: 255, nullable: true)]
    #[Groups(['page:details'])]
    private ?string $metaTitle = null;

    #[ORM\Column(name: 'page_Meta_Description', type: Types::TEXT, nullable: true)]
    #[Groups(['page:details'])]
    private ?string $metaDescription = null;

    #[ORM\Column(name: 'page_OG_Image', type: Types::STRING, length: 500, nullable: true)]
    #[Groups(['page:details'])]
    private ?string $ogImage = null;

    // --- AUDIT TRAIL ---

    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    #[ORM\JoinColumn(name: 'page_Owner_Id', referencedColumnName: 'user_Id', nullable: true)]
    private ?ManagementUsers $createdBy = null;

    #[ORM\Column(name: 'page_Created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: ManagementUsers::class)]
    #[ORM\JoinColumn(name: 'page_Last_modifier', referencedColumnName: 'user_Id', nullable: true)]
    private ?ManagementUsers $updatedBy = null;

    #[ORM\Column(name: 'page_Last_modified_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->pageCategories = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    // --- GETTERS & SETTERS ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): static
    {
        $this->name = $name; return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function setSlug(?string $slug): static
    {
        $this->slug = $slug; return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }
    public function setLink(?string $link): static
    {
        $this->link = $link; return $this;
    }

    #[Groups(['page:read'])]
    public function getParent(): ?int
    {
        return $this->parent?->getId();
    }
    public function setParent(?self $parent): static
    {
        $this->parent = $parent; return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }
    public function setActive(?int $active): static
    {
        $this->active = $active; return $this;
    }

    public function getStatus(): ?Code
    {
        return $this->status;
    }

    public function setStatus(?Code $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }
    public function setOrder(?string $order): static
    {
        $this->order = $order; return $this;
    }

    public function getBlocks(): ?array
    {
        return $this->blocks;
    }
    public function setBlocks(?array $blocks): static
    {
        $this->blocks = $blocks; return $this;
    }

    public function getSettings(): ?array
    {
        return $this->settings;
    }
    public function setSettings(?array $settings): static
    {
        $this->settings = $settings; return $this;
    }

    public function getValidFrom(): ?\DateTimeInterface
    {
        return $this->validFrom;
    }
    public function setValidFrom(?\DateTimeInterface $validFrom): static
    {
        $this->validFrom = $validFrom; return $this;
    }

    public function getValidTo(): ?\DateTimeInterface
    {
        return $this->validTo;
    }
    public function setValidTo(?\DateTimeInterface $validTo): static
    {
        $this->validTo = $validTo; return $this;
    }
    #[Groups(['page:details', 'page:read'])]
    public function getApiItem(): ?V3Items
    {
        return $this->apiItem;
    }
    public function setApiItem(?V3Items $apiItem): static
    {
        $this->apiItem = $apiItem; return $this;
    }

    public function isIgnoreApi(): bool
    {
        return $this->ignoreApi;
    }
    public function setIgnoreApi(bool $ignoreApi): static
    {
        $this->ignoreApi = $ignoreApi; return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }
    public function setMetaTitle(?string $metaTitle): static
    {
        $this->metaTitle = $metaTitle; return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }
    public function setMetaDescription(?string $metaDescription): static
    {
        $this->metaDescription = $metaDescription; return $this;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }
    public function setOgImage(?string $ogImage): static
    {
        $this->ogImage = $ogImage; return $this;
    }

    public function getCreatedBy(): ?ManagementUsers
    {
        return $this->createdBy;
    }
    public function setCreatedBy(?ManagementUsers $createdBy): static
    {
        $this->createdBy = $createdBy; return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt; return $this;
    }

    public function getUpdatedBy(): ?ManagementUsers
    {
        return $this->updatedBy;
    }
    public function setUpdatedBy(?ManagementUsers $updatedBy): static
    {
        $this->updatedBy = $updatedBy; return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt; return $this;
    }



    /**
 * Helper functie voor de API serializer
 */
    #[Groups(['page:details', 'page:read'])]
    public function getCategories(): array
    {
        $categories = [];
        foreach ($this->pageCategories as $relation) {
            $cat = $relation->getPcCategory();
            if ($cat) {
                $categories[] = [
                'id' => $cat->getCategoryId(),
                'name' => $cat->getCategoryName(),
                ];
            }
        }

        return $categories;
    }
}
