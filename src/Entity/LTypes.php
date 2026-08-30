<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * LTypes
 */
#[ORM\Table(name: 'L_Types')]
#[ORM\Entity]
class LTypes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'LT_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $ltId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'LT_Naam', type: 'string', length: 500, nullable: false)]
    private $ltNaam;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'LT_Beschrijving', type: 'string', length: 500, nullable: true)]
    private $ltBeschrijving;

    /**
     * @var string
     */
    #[ORM\Column(name: 'LT_Default_Level', type: 'decimal', precision: 19, scale: 4, nullable: false)]
    private $ltDefaultLevel;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'LT_Added', type: 'datetime', nullable: false, options: ['default' => null])]
    private $ltAdded = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'LT_Owner', type: 'integer', nullable: false)]
    private $ltOwner;

    public function getLtId(): ?int
    {
        return $this->ltId;
    }

    public function getLtNaam(): ?string
    {
        return $this->ltNaam;
    }

    public function setLtNaam(string $ltNaam): static
    {
        $this->ltNaam = $ltNaam;

        return $this;
    }

    public function getLtBeschrijving(): ?string
    {
        return $this->ltBeschrijving;
    }

    public function setLtBeschrijving(?string $ltBeschrijving): static
    {
        $this->ltBeschrijving = $ltBeschrijving;

        return $this;
    }

    public function getLtDefaultLevel(): ?string
    {
        return $this->ltDefaultLevel;
    }

    public function setLtDefaultLevel(string $ltDefaultLevel): static
    {
        $this->ltDefaultLevel = $ltDefaultLevel;

        return $this;
    }

    public function getLtAdded(): ?\DateTime
    {
        return $this->ltAdded;
    }

    public function setLtAdded(\DateTime $ltAdded): static
    {
        $this->ltAdded = $ltAdded;

        return $this;
    }

    public function getLtOwner(): ?int
    {
        return $this->ltOwner;
    }

    public function setLtOwner(int $ltOwner): static
    {
        $this->ltOwner = $ltOwner;

        return $this;
    }


}
