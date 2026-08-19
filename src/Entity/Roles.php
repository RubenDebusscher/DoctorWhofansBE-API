<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * Roles
 */
#[ORM\Table(name: 'Roles')]
#[ORM\Entity]
class Roles
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'Rol_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $rolId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Rol_Naam', type: 'string', length: 50, nullable: false)]
    private $rolNaam;

    public function getRolId(): ?int
    {
        return $this->rolId;
    }

    public function getRolNaam(): ?string
    {
        return $this->rolNaam;
    }

    public function setRolNaam(string $rolNaam): static
    {
        $this->rolNaam = $rolNaam;

        return $this;
    }


}
