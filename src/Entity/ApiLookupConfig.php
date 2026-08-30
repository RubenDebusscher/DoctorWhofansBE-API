<?php

namespace App\Entity;

use App\Repository\ApiLookupConfigRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApiLookupConfigRepository::class)]
#[ORM\Table(name: 'api_lookup_config')]
class ApiLookupConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    /**
     * Het slug in de URL, bijv: 'episodes', 'shows', 'actors'
     */
    #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
    private ?string $entitySlug = null;

    /**
     * De volledige FQCN PHP klasse, bijv: 'App\Entity\ApiEpisodes'
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $entityClass = null;

    /**
     * De getter/property naam voor het ID, bijv: 'episodeid' of 'id'
     */
    #[ORM\Column(type: Types::STRING, length: 50, options: ['default' => 'id'])]
    private string $idColumn = 'id';

    /**
     * De getter/property naam voor de weergavenaam, bijv: 'title' of 'name'
     */
    #[ORM\Column(type: Types::STRING, length: 50, options: ['default' => 'name'])]
    private string $valueColumn = 'name';

    /**
     * Array van veldnamen die als GET parameter gefilterd mogen worden,
     * bijv: ["seasonid", "showid", "status"]
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $allowedFilters = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // --- GETTERS & SETTERS ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntitySlug(): ?string
    {
        return $this->entitySlug;
    }

    public function setEntitySlug(string $entitySlug): static
    {
        $this->entitySlug = strtolower(trim($entitySlug));
        return $this;
    }

    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    public function setEntityClass(string $entityClass): static
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getIdColumn(): string
    {
        return $this->idColumn;
    }

    public function setIdColumn(string $idColumn): static
    {
        $this->idColumn = $idColumn;
        return $this;
    }

    public function getValueColumn(): string
    {
        return $this->valueColumn;
    }

    public function setValueColumn(string $valueColumn): static
    {
        $this->valueColumn = $valueColumn;
        return $this;
    }

    public function getAllowedFilters(): ?array
    {
        return $this->allowedFilters ?? [];
    }

    public function setAllowedFilters(?array $allowedFilters): static
    {
        $this->allowedFilters = $allowedFilters;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
