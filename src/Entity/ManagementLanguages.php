<?php

namespace App\Entity;

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'management__languages')]
class ManagementLanguages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'language_Id', type: 'integer')]
    private ?int $languageId = null;

    #[ORM\Column(name: 'language_Name', type: 'string', length: 255)]
    private ?string $languageName = null;

    #[ORM\Column(name: 'language_Owner_Id', type: 'integer', nullable: true)]
    private ?int $languageOwnerId = null;

    #[ORM\Column(name: 'language_Created_at', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $languageCreatedAt = null;

    #[ORM\Column(name: 'language_Last_modifier', type: 'integer', nullable: true)]
    private ?int $languageLastModifier = null;

    #[ORM\Column(name: 'language_Last_modified_at', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $languageLastModifiedAt = null;

    #[ORM\Column(name: 'language_LongName', type: 'string', length: 255, nullable: true)]
    private ?string $languageLongName = null;

    // --- Getters and Setters ---

    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    public function getLanguageName(): ?string
    {
        return $this->languageName;
    }

    public function setLanguageName(string $languageName): self
    {
        $this->languageName = $languageName;
        return $this;
    }

    public function getLanguageLongName(): ?string
    {
        return $this->languageLongName;
    }

    public function setLanguageLongName(?string $languageLongName): self
    {
        $this->languageLongName = $languageLongName;
        return $this;
    }

    public function getLanguageOwnerId(): ?int
    {
        return $this->languageOwnerId;
    }

    public function setLanguageOwnerId(?int $languageOwnerId): self
    {
        $this->languageOwnerId = $languageOwnerId;
        return $this;
    }

    public function getLanguageCreatedAt(): ?\DateTimeInterface
    {
        return $this->languageCreatedAt;
    }

    public function setLanguageCreatedAt(?\DateTimeInterface $languageCreatedAt): self
    {
        $this->languageCreatedAt = $languageCreatedAt;
        return $this;
    }

    public function getLanguageLastModifier(): ?int
    {
        return $this->languageLastModifier;
    }

    public function setLanguageLastModifier(?int $languageLastModifier): self
    {
        $this->languageLastModifier = $languageLastModifier;
        return $this;
    }

    public function getLanguageLastModifiedAt(): ?\DateTimeInterface
    {
        return $this->languageLastModifiedAt;
    }

    public function setLanguageLastModifiedAt(?\DateTimeInterface $languageLastModifiedAt): self
    {
        $this->languageLastModifiedAt = $languageLastModifiedAt;
        return $this;
    }
}
