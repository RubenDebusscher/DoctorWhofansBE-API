<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiCharacterTypes
 */
#[ORM\Table(name: 'api__character_Types')]
#[ORM\Entity]
class ApiCharacterTypes
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'CT_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $ctId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'CT_Name', type: 'text', length: 65535, nullable: false)]
    private $ctName;

    public function getCtId(): ?int
    {
        return $this->ctId;
    }

    public function getCtName(): ?string
    {
        return $this->ctName;
    }

    public function setCtName(string $ctName): static
    {
        $this->ctName = $ctName;

        return $this;
    }


}
