<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ContentQuotesCharacters
 */
#[ORM\Table(name: 'content__quotes_characters')]
#[ORM\Index(name: 'QC_Quote0', columns: ['QC_Quote_Id'])]
#[ORM\Index(name: 'DL_Owner_idx', columns: ['QC_Owner_Id'])]
#[ORM\Index(name: 'DL_Last_Modifier_idx', columns: ['QC_Last_modifier'])]
#[ORM\Index(name: 'IDX_D577D63814C58B13', columns: ['QC_Character_Id'])]
#[ORM\Entity]
class ContentQuotesCharacters
{
    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'QC_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $qcCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'QC_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $qcLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'QC_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $qcLastModifier;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'QC_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $qcOwner;

    /**
     * @var \ApiCharacters
     */
    #[ORM\JoinColumn(name: 'QC_Character_Id', referencedColumnName: 'character_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ApiCharacters::class)]
    private $qcCharacter;

    /**
     * @var \ContentQuotes
     */
    #[ORM\JoinColumn(name: 'QC_Quote_Id', referencedColumnName: 'quote_Id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: \ContentQuotes::class)]
    private $qcQuote;

    public function getQcCreatedAt(): ?\DateTime
    {
        return $this->qcCreatedAt;
    }

    public function setQcCreatedAt(\DateTime $qcCreatedAt): static
    {
        $this->qcCreatedAt = $qcCreatedAt;

        return $this;
    }

    public function getQcLastModifiedAt(): ?\DateTime
    {
        return $this->qcLastModifiedAt;
    }

    public function setQcLastModifiedAt(\DateTime $qcLastModifiedAt): static
    {
        $this->qcLastModifiedAt = $qcLastModifiedAt;

        return $this;
    }

    public function getQcLastModifier(): ?ManagementUsers
    {
        return $this->qcLastModifier;
    }

    public function setQcLastModifier(?ManagementUsers $qcLastModifier): static
    {
        $this->qcLastModifier = $qcLastModifier;

        return $this;
    }

    public function getQcOwner(): ?ManagementUsers
    {
        return $this->qcOwner;
    }

    public function setQcOwner(?ManagementUsers $qcOwner): static
    {
        $this->qcOwner = $qcOwner;

        return $this;
    }

    public function getQcCharacter(): ?ApiCharacters
    {
        return $this->qcCharacter;
    }

    public function setQcCharacter(ApiCharacters $qcCharacter): static
    {
        $this->qcCharacter = $qcCharacter;

        return $this;
    }

    public function getQcQuote(): ?ContentQuotes
    {
        return $this->qcQuote;
    }

    public function setQcQuote(ContentQuotes $qcQuote): static
    {
        $this->qcQuote = $qcQuote;

        return $this;
    }


}
