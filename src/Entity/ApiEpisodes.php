<?php

namespace App\Entity;



use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiEpisodes
 */
#[ORM\Table(name: 'api__episodes')]
#[ORM\Index(name: 'API__episodes__Owner__ID', columns: ['episode_Owner_Id'])]
#[ORM\Index(name: 'API__episodes__Last__Modified__User__ID', columns: ['episode_Last_modifier'])]
#[ORM\Index(name: 'serial_id', columns: ['episode_Serial_Id'])]
#[ORM\Entity]
class ApiEpisodes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'episode_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $episodeId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'episode_Title', type: 'text', length: 65535, nullable: false)]
    private $episodeTitle;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'episode_Story', type: 'text', length: 65535, nullable: true)]
    private $episodeStory;

    /**
     * @var string
     */
    #[ORM\Column(name: 'episode_Order', type: 'text', length: 65535, nullable: false)]
    private $episodeOrder;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'episode_Original_airdate', type: 'datetime', nullable: true)]
    private $episodeOriginalAirdate;

    /**
     * @var string
     */
    #[ORM\Column(name: 'episode_OriginalTimeZone', type: 'text', length: 65535, nullable: false, options: ['default' => "'00:00:00'"])]
    private $episodeOriginaltimezone = '\'00:00:00\'';

    /**
     * @var string
     */
    #[ORM\Column(name: 'episode_Original_Network', type: 'text', length: 65535, nullable: false)]
    private $episodeOriginalNetwork;

    /**
     * @var float|null
     */
    #[ORM\Column(name: 'episode_Runtime_in_seconds', type: 'float', precision: 10, scale: 0, nullable: true)]
    private $episodeRuntimeInSeconds;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'episode_UK_viewers', type: 'decimal', precision: 10, scale: 0, nullable: true)]
    private $episodeUkViewers;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'episode_Appreciation_index', type: 'integer', nullable: true)]
    private $episodeAppreciationIndex;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'episode_Recreated', type: 'string', length: 255, nullable: true)]
    private $episodeRecreated;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'episode_Synopsis', type: 'string', length: 5000, nullable: true)]
    private $episodeSynopsis;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'episode_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $episodeCreatedAt = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'episode_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $episodeLastModifiedAt = null;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'episode_Owner_Id', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $episodeOwner;

    /**
     * @var \ManagementUsers
     */
    #[ORM\JoinColumn(name: 'episode_Last_modifier', referencedColumnName: 'user_Id')]
    #[ORM\ManyToOne(targetEntity: \ManagementUsers::class)]
    private $episodeLastModifier;

    /**
     * @var \ApiSerials
     */
    #[ORM\JoinColumn(name: 'episode_Serial_Id', referencedColumnName: 'serial_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiSerials::class)]
    private $episodeSerial;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    #[ORM\JoinTable(name: 'api__episodes_reconstructions')]
    #[ORM\JoinColumn(name: 'ER_Episode_Id', referencedColumnName: 'episode_Id')]
    #[ORM\InverseJoinColumn(name: 'ER_Reconstruction_Id', referencedColumnName: 'reconstruction_Id')]
    #[ORM\ManyToMany(targetEntity: \ApiReconstructions::class, inversedBy: 'erEpisode')]
    private $erReconstruction = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->erReconstruction = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getEpisodeId(): ?int
    {
        return $this->episodeId;
    }

    public function getEpisodeTitle(): ?string
    {
        return $this->episodeTitle;
    }

    public function setEpisodeTitle(string $episodeTitle): static
    {
        $this->episodeTitle = $episodeTitle;

        return $this;
    }

    public function getEpisodeStory(): ?string
    {
        return $this->episodeStory;
    }

    public function setEpisodeStory(?string $episodeStory): static
    {
        $this->episodeStory = $episodeStory;

        return $this;
    }

    public function getEpisodeOrder(): ?string
    {
        return $this->episodeOrder;
    }

    public function setEpisodeOrder(string $episodeOrder): static
    {
        $this->episodeOrder = $episodeOrder;

        return $this;
    }

    public function getEpisodeOriginalAirdate(): ?\DateTime
    {
        return $this->episodeOriginalAirdate;
    }

    public function setEpisodeOriginalAirdate(?\DateTime $episodeOriginalAirdate): static
    {
        $this->episodeOriginalAirdate = $episodeOriginalAirdate;

        return $this;
    }

    public function getEpisodeOriginaltimezone(): ?string
    {
        return $this->episodeOriginaltimezone;
    }

    public function setEpisodeOriginaltimezone(string $episodeOriginaltimezone): static
    {
        $this->episodeOriginaltimezone = $episodeOriginaltimezone;

        return $this;
    }

    public function getEpisodeOriginalNetwork(): ?string
    {
        return $this->episodeOriginalNetwork;
    }

    public function setEpisodeOriginalNetwork(string $episodeOriginalNetwork): static
    {
        $this->episodeOriginalNetwork = $episodeOriginalNetwork;

        return $this;
    }

    public function getEpisodeRuntimeInSeconds(): ?float
    {
        return $this->episodeRuntimeInSeconds;
    }

    public function setEpisodeRuntimeInSeconds(?float $episodeRuntimeInSeconds): static
    {
        $this->episodeRuntimeInSeconds = $episodeRuntimeInSeconds;

        return $this;
    }

    public function getEpisodeUkViewers(): ?string
    {
        return $this->episodeUkViewers;
    }

    public function setEpisodeUkViewers(?string $episodeUkViewers): static
    {
        $this->episodeUkViewers = $episodeUkViewers;

        return $this;
    }

    public function getEpisodeAppreciationIndex(): ?int
    {
        return $this->episodeAppreciationIndex;
    }

    public function setEpisodeAppreciationIndex(?int $episodeAppreciationIndex): static
    {
        $this->episodeAppreciationIndex = $episodeAppreciationIndex;

        return $this;
    }

    public function getEpisodeRecreated(): ?string
    {
        return $this->episodeRecreated;
    }

    public function setEpisodeRecreated(?string $episodeRecreated): static
    {
        $this->episodeRecreated = $episodeRecreated;

        return $this;
    }

    public function getEpisodeSynopsis(): ?string
    {
        return $this->episodeSynopsis;
    }

    public function setEpisodeSynopsis(?string $episodeSynopsis): static
    {
        $this->episodeSynopsis = $episodeSynopsis;

        return $this;
    }

    public function getEpisodeCreatedAt(): ?\DateTime
    {
        return $this->episodeCreatedAt;
    }

    public function setEpisodeCreatedAt(\DateTime $episodeCreatedAt): static
    {
        $this->episodeCreatedAt = $episodeCreatedAt;

        return $this;
    }

    public function getEpisodeLastModifiedAt(): ?\DateTime
    {
        return $this->episodeLastModifiedAt;
    }

    public function setEpisodeLastModifiedAt(\DateTime $episodeLastModifiedAt): static
    {
        $this->episodeLastModifiedAt = $episodeLastModifiedAt;

        return $this;
    }

    public function getEpisodeOwner(): ?ManagementUsers
    {
        return $this->episodeOwner;
    }

    public function setEpisodeOwner(?ManagementUsers $episodeOwner): static
    {
        $this->episodeOwner = $episodeOwner;

        return $this;
    }

    public function getEpisodeLastModifier(): ?ManagementUsers
    {
        return $this->episodeLastModifier;
    }

    public function setEpisodeLastModifier(?ManagementUsers $episodeLastModifier): static
    {
        $this->episodeLastModifier = $episodeLastModifier;

        return $this;
    }

    public function getEpisodeSerial(): ?ApiSerials
    {
        return $this->episodeSerial;
    }

    public function setEpisodeSerial(?ApiSerials $episodeSerial): static
    {
        $this->episodeSerial = $episodeSerial;

        return $this;
    }

    /**
     * @return Collection<int, ApiReconstructions>
     */
    public function getErReconstruction(): Collection
    {
        return $this->erReconstruction;
    }

    public function addErReconstruction(ApiReconstructions $erReconstruction): static
    {
        if (!$this->erReconstruction->contains($erReconstruction)) {
            $this->erReconstruction->add($erReconstruction);
        }

        return $this;
    }

    public function removeErReconstruction(ApiReconstructions $erReconstruction): static
    {
        $this->erReconstruction->removeElement($erReconstruction);

        return $this;
    }

}
