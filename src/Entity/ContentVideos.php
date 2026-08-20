<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ContentVideos
 */
#[ORM\Table(name: 'content__videos')]
#[ORM\Index(name: 'Video_Owner_idx', columns: ['video_Owner_Id'])]
#[ORM\Index(name: 'Video_Last_Modifier_idx', columns: ['video_Last_modifier'])]
#[ORM\Entity]
class ContentVideos
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'video_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $videoId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'video_Name', type: 'string', length: 500, nullable: true)]
    private $videoName;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'video_URL', type: 'string', length: 500, nullable: true)]
    private $videoUrl;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'video_Image', type: 'string', length: 45, nullable: true)]
    private $videoImage;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'video_Type', type: 'string', length: 50, nullable: true)]
    private $videoType;

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'video_Spoiler', type: 'boolean', nullable: true)]
    private $videoSpoiler;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'video_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $videoCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'video_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $videoLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'video_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $videoLastModifier;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'video_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $videoOwner;

    public function getVideoId(): ?int
    {
        return $this->videoId;
    }

    public function getVideoName(): ?string
    {
        return $this->videoName;
    }

    public function setVideoName(?string $videoName): static
    {
        $this->videoName = $videoName;

        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(?string $videoUrl): static
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    public function getVideoImage(): ?string
    {
        return $this->videoImage;
    }

    public function setVideoImage(?string $videoImage): static
    {
        $this->videoImage = $videoImage;

        return $this;
    }

    public function getVideoType(): ?string
    {
        return $this->videoType;
    }

    public function setVideoType(?string $videoType): static
    {
        $this->videoType = $videoType;

        return $this;
    }

    public function isVideoSpoiler(): ?bool
    {
        return $this->videoSpoiler;
    }

    public function setVideoSpoiler(?bool $videoSpoiler): static
    {
        $this->videoSpoiler = $videoSpoiler;

        return $this;
    }

    public function getVideoCreatedAt(): ?\DateTime
    {
        return $this->videoCreatedAt;
    }

    public function setVideoCreatedAt(\DateTime $videoCreatedAt): static
    {
        $this->videoCreatedAt = $videoCreatedAt;

        return $this;
    }

    public function getVideoLastModifiedAt(): ?\DateTime
    {
        return $this->videoLastModifiedAt;
    }

    public function setVideoLastModifiedAt(\DateTime $videoLastModifiedAt): static
    {
        $this->videoLastModifiedAt = $videoLastModifiedAt;

        return $this;
    }

    public function getVideoLastModifier(): ?ManagementUsers
    {
        return $this->videoLastModifier;
    }

    public function setVideoLastModifier(?ManagementUsers $videoLastModifier): static
    {
        $this->videoLastModifier = $videoLastModifier;

        return $this;
    }

    public function getVideoOwner(): ?ManagementUsers
    {
        return $this->videoOwner;
    }

    public function setVideoOwner(?ManagementUsers $videoOwner): static
    {
        $this->videoOwner = $videoOwner;

        return $this;
    }


}
