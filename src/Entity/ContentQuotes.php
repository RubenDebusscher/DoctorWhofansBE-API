<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContentQuotes
 */
#[ORM\Table(name: 'content__quotes')]
#[ORM\Index(name: 'quote_Last_Modifier_idx', columns: ['quote_Last_modifier'])]
#[ORM\Index(name: 'quote_Serial_idx', columns: ['quote_Episode'])]
#[ORM\Index(name: 'quote_Owner_id', columns: ['quote_Owner_Id'])]
#[ORM\Entity]
class ContentQuotes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'quote_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $quoteId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'quote_Item', type: 'text', length: 0, nullable: true)]
    private $quoteItem;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'quote_Image', type: 'string', length: 255, nullable: true)]
    private $quoteImage;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'quote_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $quoteCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'quote_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $quoteLastModifiedAt = null;

    /**
     * @var string
     */
    #[ORM\Column(name: 'ref_image_mimetype', type: 'string', length: 10, nullable: false)]
    private $refImageMimetype;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'quote_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $quoteOwner;

    /**
     * @var \ManagementPages
     */
    #[ORM\JoinColumn(name: 'quote_Episode', referencedColumnName: 'page_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementPages::class)]
    private $quoteEpisode;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'quote_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $quoteLastModifier;

    public function getQuoteId(): ?int
    {
        return $this->quoteId;
    }

    public function getQuoteItem(): ?string
    {
        return $this->quoteItem;
    }

    public function setQuoteItem(?string $quoteItem): static
    {
        $this->quoteItem = $quoteItem;

        return $this;
    }

    public function getQuoteImage(): ?string
    {
        return $this->quoteImage;
    }

    public function setQuoteImage(?string $quoteImage): static
    {
        $this->quoteImage = $quoteImage;

        return $this;
    }

    public function getQuoteCreatedAt(): ?\DateTime
    {
        return $this->quoteCreatedAt;
    }

    public function setQuoteCreatedAt(\DateTime $quoteCreatedAt): static
    {
        $this->quoteCreatedAt = $quoteCreatedAt;

        return $this;
    }

    public function getQuoteLastModifiedAt(): ?\DateTime
    {
        return $this->quoteLastModifiedAt;
    }

    public function setQuoteLastModifiedAt(\DateTime $quoteLastModifiedAt): static
    {
        $this->quoteLastModifiedAt = $quoteLastModifiedAt;

        return $this;
    }

    public function getRefImageMimetype(): ?string
    {
        return $this->refImageMimetype;
    }

    public function setRefImageMimetype(string $refImageMimetype): static
    {
        $this->refImageMimetype = $refImageMimetype;

        return $this;
    }

    public function getQuoteOwner(): ?ManagementUsers
    {
        return $this->quoteOwner;
    }

    public function setQuoteOwner(?ManagementUsers $quoteOwner): static
    {
        $this->quoteOwner = $quoteOwner;

        return $this;
    }

    public function getQuoteEpisode(): ?ManagementPages
    {
        return $this->quoteEpisode;
    }

    public function setQuoteEpisode(?ManagementPages $quoteEpisode): static
    {
        $this->quoteEpisode = $quoteEpisode;

        return $this;
    }

    public function getQuoteLastModifier(): ?ManagementUsers
    {
        return $this->quoteLastModifier;
    }

    public function setQuoteLastModifier(?ManagementUsers $quoteLastModifier): static
    {
        $this->quoteLastModifier = $quoteLastModifier;

        return $this;
    }


}
