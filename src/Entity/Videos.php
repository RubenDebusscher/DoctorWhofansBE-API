<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Videos
 */
#[ORM\Table(name: 'Videos')]
#[ORM\Entity]
class Videos
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Video_Name', type: 'text', length: 16777215, nullable: false)]
    private $videoName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Video_URL', type: 'text', length: 0, nullable: false)]
    private $videoUrl;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Video_Image', type: 'text', length: 65535, nullable: false)]
    private $videoImage;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Video_Beschrijving', type: 'text', length: 0, nullable: false)]
    private $videoBeschrijving;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Video_Type', type: 'string', length: 255, nullable: false, options: ['default' => 'Youtube'])]
    private $videoType = 'Youtube';

    /**
     * @var int
     */
    #[ORM\Column(name: 'Spoiler', type: 'integer', nullable: false)]
    private $spoiler = '0';

    /**
     * @var int
     */
    #[ORM\Column(name: 'V_Owner', type: 'integer', nullable: false)]
    private $vOwner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoName(): ?string
    {
        return $this->videoName;
    }

    public function setVideoName(string $videoName): static
    {
        $this->videoName = $videoName;

        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(string $videoUrl): static
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    public function getVideoImage(): ?string
    {
        return $this->videoImage;
    }

    public function setVideoImage(string $videoImage): static
    {
        $this->videoImage = $videoImage;

        return $this;
    }

    public function getVideoBeschrijving(): ?string
    {
        return $this->videoBeschrijving;
    }

    public function setVideoBeschrijving(string $videoBeschrijving): static
    {
        $this->videoBeschrijving = $videoBeschrijving;

        return $this;
    }

    public function getVideoType(): ?string
    {
        return $this->videoType;
    }

    public function setVideoType(string $videoType): static
    {
        $this->videoType = $videoType;

        return $this;
    }

    public function getSpoiler(): ?int
    {
        return $this->spoiler;
    }

    public function setSpoiler(int $spoiler): static
    {
        $this->spoiler = $spoiler;

        return $this;
    }

    public function getVOwner(): ?int
    {
        return $this->vOwner;
    }

    public function setVOwner(int $vOwner): static
    {
        $this->vOwner = $vOwner;

        return $this;
    }


}
