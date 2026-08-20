<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * LPaginaTypes
 */
#[ORM\Table(name: 'L_Pagina_Types')]
#[ORM\Entity]
class LPaginaTypes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'LPT_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $lptId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'LPT_Naam', type: 'string', length: 500, nullable: false)]
    private $lptNaam;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'LPT_Beschrijving', type: 'string', length: 500, nullable: true)]
    private $lptBeschrijving;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'LPT_Added', type: 'datetime', nullable: false, options: ['default' => null])]
    private $lptAdded = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'LPT_Owner', type: 'integer', nullable: false)]
    private $lptOwner;

    public function getLptId(): ?int
    {
        return $this->lptId;
    }

    public function getLptNaam(): ?string
    {
        return $this->lptNaam;
    }

    public function setLptNaam(string $lptNaam): static
    {
        $this->lptNaam = $lptNaam;

        return $this;
    }

    public function getLptBeschrijving(): ?string
    {
        return $this->lptBeschrijving;
    }

    public function setLptBeschrijving(?string $lptBeschrijving): static
    {
        $this->lptBeschrijving = $lptBeschrijving;

        return $this;
    }

    public function getLptAdded(): ?\DateTime
    {
        return $this->lptAdded;
    }

    public function setLptAdded(\DateTime $lptAdded): static
    {
        $this->lptAdded = $lptAdded;

        return $this;
    }

    public function getLptOwner(): ?int
    {
        return $this->lptOwner;
    }

    public function setLptOwner(int $lptOwner): static
    {
        $this->lptOwner = $lptOwner;

        return $this;
    }


}
