<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContentVideodescriptions
 */
#[ORM\Table(name: 'content__videodescriptions')]
#[ORM\Index(name: 'videodescription_Video_idx', columns: ['videodescription_Video'])]
#[ORM\Index(name: 'videodescription_Owner_idx', columns: ['videodescription_Owner_Id'])]
#[ORM\Index(name: 'videodescription_Last_Modifier_idx', columns: ['videodescription_Last_modifier'])]
#[ORM\Entity]
class ContentVideodescriptions
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'videodescription_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $videodescriptionId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'videodescription_Description', type: 'text', length: 0, nullable: true)]
    private $videodescriptionDescription;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'videodescription_Created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $videodescriptionCreatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'videodescription_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $videodescriptionLastModifiedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'videodescription_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $videodescriptionOwner;

    /**
     * @var \ContentVideos
     */
    #[ORM\JoinColumn(name: 'videodescription_Video', referencedColumnName: 'video_Id')]
    #[ORM\ManyToOne(targetEntity: \ContentVideos::class)]
    private $videodescriptionVideo;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'videodescription_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $videodescriptionLastModifier;

    public function getVideodescriptionId(): ?int
    {
        return $this->videodescriptionId;
    }

    public function getVideodescriptionDescription(): ?string
    {
        return $this->videodescriptionDescription;
    }

    public function setVideodescriptionDescription(?string $videodescriptionDescription): static
    {
        $this->videodescriptionDescription = $videodescriptionDescription;

        return $this;
    }

    public function getVideodescriptionCreatedAt(): ?\DateTime
    {
        return $this->videodescriptionCreatedAt;
    }

    public function setVideodescriptionCreatedAt(\DateTime $videodescriptionCreatedAt): static
    {
        $this->videodescriptionCreatedAt = $videodescriptionCreatedAt;

        return $this;
    }

    public function getVideodescriptionLastModifiedAt(): ?\DateTime
    {
        return $this->videodescriptionLastModifiedAt;
    }

    public function setVideodescriptionLastModifiedAt(\DateTime $videodescriptionLastModifiedAt): static
    {
        $this->videodescriptionLastModifiedAt = $videodescriptionLastModifiedAt;

        return $this;
    }

    public function getVideodescriptionOwner(): ?ManagementUsers
    {
        return $this->videodescriptionOwner;
    }

    public function setVideodescriptionOwner(?ManagementUsers $videodescriptionOwner): static
    {
        $this->videodescriptionOwner = $videodescriptionOwner;

        return $this;
    }

    public function getVideodescriptionVideo(): ?ContentVideos
    {
        return $this->videodescriptionVideo;
    }

    public function setVideodescriptionVideo(?ContentVideos $videodescriptionVideo): static
    {
        $this->videodescriptionVideo = $videodescriptionVideo;

        return $this;
    }

    public function getVideodescriptionLastModifier(): ?ManagementUsers
    {
        return $this->videodescriptionLastModifier;
    }

    public function setVideodescriptionLastModifier(?ManagementUsers $videodescriptionLastModifier): static
    {
        $this->videodescriptionLastModifier = $videodescriptionLastModifier;

        return $this;
    }


}
