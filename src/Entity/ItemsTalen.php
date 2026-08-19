<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ItemsTalen
 */
#[ORM\Table(name: 'items_talen')]
#[ORM\Entity]
class ItemsTalen
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'item_id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private $itemId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'taal_id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private $taalId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'IT_Owner', type: 'integer', nullable: false)]
    private $itOwner;

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function getTaalId(): ?int
    {
        return $this->taalId;
    }

    public function getItOwner(): ?int
    {
        return $this->itOwner;
    }

    public function setItOwner(int $itOwner): static
    {
        $this->itOwner = $itOwner;

        return $this;
    }


}
