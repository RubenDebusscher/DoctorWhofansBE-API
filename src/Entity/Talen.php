<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * Talen
 */
#[ORM\Table(name: 'talen')]
#[ORM\Entity]
class Talen
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'taal_id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $taalId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'taal_naam', type: 'string', length: 20, nullable: false)]
    private $taalNaam;

    /**
     * @var int
     */
    #[ORM\Column(name: 'T_Owner', type: 'integer', nullable: false)]
    private $tOwner;

    public function getTaalId(): ?int
    {
        return $this->taalId;
    }

    public function getTaalNaam(): ?string
    {
        return $this->taalNaam;
    }

    public function setTaalNaam(string $taalNaam): static
    {
        $this->taalNaam = $taalNaam;

        return $this;
    }

    public function getTOwner(): ?int
    {
        return $this->tOwner;
    }

    public function setTOwner(int $tOwner): static
    {
        $this->tOwner = $tOwner;

        return $this;
    }


}
