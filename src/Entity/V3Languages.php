<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * V3Languages
 */
#[ORM\Table(name: 'V3__Languages')]
#[ORM\Entity]
class V3Languages
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'LanguageID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $languageid;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Name', type: 'string', length: 255, nullable: false)]
    private $name;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Code', type: 'string', length: 10, nullable: false)]
    private $code;

    /**
     * @var int
     */
    #[ORM\Column(name: 'created_by', type: 'integer', nullable: false)]
    private $createdBy;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     */
    #[ORM\Column(name: 'updated_by', type: 'integer', nullable: false)]
    private $updatedBy;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $updatedAt = 'CURRENT_TIMESTAMP';

    public function getLanguageid(): ?int
    {
        return $this->languageid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(int $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}
