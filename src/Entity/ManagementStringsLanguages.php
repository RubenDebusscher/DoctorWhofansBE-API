<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use App\Entity\ManagementLanguages; // <-- Deze import is essentieel!


/**
 * ManagementStringsLanguages
 */
#[ORM\Table(name: 'management__strings_languages')]
#[ORM\Index(name: 'Last_Modifier_idx', columns: ['SL_Last_modifier'])]
#[ORM\Index(name: 'String_Id_idx', columns: ['string_Id'])]
#[ORM\Index(name: 'Language_Id_idx', columns: ['language_Id'])]
#[ORM\Index(name: 'Owner_Id_idx', columns: ['SL_Owner_Id'])]
#[ORM\Entity]
class ManagementStringsLanguages
{
    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'SL_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $slCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'SL_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $slLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'SL_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $slOwner;

    /**
     * @var \ManagementLanguages
     */
    #[ORM\JoinColumn(name: 'language_Id', referencedColumnName: 'language_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: ManagementLanguages::class)]
    private ?ManagementLanguages $language;

    /**
     * @var \ManagementStrings
     */
    #[ORM\JoinColumn(name: 'string_Id', referencedColumnName: 'string_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ManagementStrings::class)]
    private $string;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'SL_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $slLastModifier;

    public function getSlCreatedAt(): ?\DateTime
    {
        return $this->slCreatedAt;
    }

    public function setSlCreatedAt(\DateTime $slCreatedAt): static
    {
        $this->slCreatedAt = $slCreatedAt;

        return $this;
    }

    public function getSlLastModifiedAt(): ?\DateTime
    {
        return $this->slLastModifiedAt;
    }

    public function setSlLastModifiedAt(\DateTime $slLastModifiedAt): static
    {
        $this->slLastModifiedAt = $slLastModifiedAt;

        return $this;
    }

    public function getSlOwner(): ?ManagementUsers
    {
        return $this->slOwner;
    }

    public function setSlOwner(?ManagementUsers $slOwner): static
    {
        $this->slOwner = $slOwner;

        return $this;
    }

    public function getLanguage(): ?ManagementLanguages
    {
        return $this->language;
    }

    public function setLanguage(ManagementLanguages $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getString(): ?ManagementStrings
    {
        return $this->string;
    }

    public function setString(ManagementStrings $string): static
    {
        $this->string = $string;

        return $this;
    }

    public function getSlLastModifier(): ?ManagementUsers
    {
        return $this->slLastModifier;
    }

    public function setSlLastModifier(?ManagementUsers $slLastModifier): static
    {
        $this->slLastModifier = $slLastModifier;

        return $this;
    }


}
