<?php

namespace App\Entity;



use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiReconstructions
 */
#[ORM\Table(name: 'api__reconstructions')]
#[ORM\Index(name: 'API__shows__Owner__ID', columns: ['reconstruction_Owner_Id'])]
#[ORM\Index(name: 'API__shows__Last__Modified__User__ID', columns: ['reconstruction_Last_modifier'])]
#[ORM\Entity]
class ApiReconstructions
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'reconstruction_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $reconstructionId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'reconstruction_Name', type: 'string', length: 50, nullable: false)]
    private $reconstructionName;

    /**
     * @var int
     */
    #[ORM\Column(name: 'reconstruction_Owner_Id', type: 'integer', nullable: false)]
    private $reconstructionOwnerId;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'reconstruction_Created_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $reconstructionCreatedAt = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'reconstruction_Last_modifier', type: 'integer', nullable: false)]
    private $reconstructionLastModifier;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'reconstruction_Last_modified_at', type: 'datetime', nullable: false, options: ['default' => null])]
    private $reconstructionLastModifiedAt = null;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    #[ORM\ManyToMany(targetEntity: \ApiEpisodes::class, mappedBy: 'erReconstruction')]
    private $erEpisode = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->erEpisode = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getReconstructionId(): ?int
    {
        return $this->reconstructionId;
    }

    public function getReconstructionName(): ?string
    {
        return $this->reconstructionName;
    }

    public function setReconstructionName(string $reconstructionName): static
    {
        $this->reconstructionName = $reconstructionName;

        return $this;
    }

    public function getReconstructionOwnerId(): ?int
    {
        return $this->reconstructionOwnerId;
    }

    public function setReconstructionOwnerId(int $reconstructionOwnerId): static
    {
        $this->reconstructionOwnerId = $reconstructionOwnerId;

        return $this;
    }

    public function getReconstructionCreatedAt(): ?\DateTime
    {
        return $this->reconstructionCreatedAt;
    }

    public function setReconstructionCreatedAt(\DateTime $reconstructionCreatedAt): static
    {
        $this->reconstructionCreatedAt = $reconstructionCreatedAt;

        return $this;
    }

    public function getReconstructionLastModifier(): ?int
    {
        return $this->reconstructionLastModifier;
    }

    public function setReconstructionLastModifier(int $reconstructionLastModifier): static
    {
        $this->reconstructionLastModifier = $reconstructionLastModifier;

        return $this;
    }

    public function getReconstructionLastModifiedAt(): ?\DateTime
    {
        return $this->reconstructionLastModifiedAt;
    }

    public function setReconstructionLastModifiedAt(\DateTime $reconstructionLastModifiedAt): static
    {
        $this->reconstructionLastModifiedAt = $reconstructionLastModifiedAt;

        return $this;
    }

    /**
     * @return Collection<int, ApiEpisodes>
     */
    public function getErEpisode(): Collection
    {
        return $this->erEpisode;
    }

    public function addErEpisode(ApiEpisodes $erEpisode): static
    {
        if (!$this->erEpisode->contains($erEpisode)) {
            $this->erEpisode->add($erEpisode);
            $erEpisode->addErReconstruction($this);
        }

        return $this;
    }

    public function removeErEpisode(ApiEpisodes $erEpisode): static
    {
        if ($this->erEpisode->removeElement($erEpisode)) {
            $erEpisode->removeErReconstruction($this);
        }

        return $this;
    }

}
