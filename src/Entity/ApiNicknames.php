<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiNicknames
 */
#[ORM\Table(name: 'api__nicknames')]
#[ORM\Index(name: 'api__nicknames_ibfk_1', columns: ['character_Id'])]
#[ORM\Index(name: 'nickname', columns: ['nickname_nickname'])]
#[ORM\Entity]
class ApiNicknames
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'nickname_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $nicknameId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'nickname_nickname', type: 'text', length: 65535, nullable: false)]
    private $nicknameNickname;

    /**
     * @var \ApiCharacters
     */
    #[ORM\JoinColumn(name: 'character_Id', referencedColumnName: 'character_Id')]
    #[ORM\ManyToOne(targetEntity: \ApiCharacters::class)]
    private $character;

    public function getNicknameId(): ?int
    {
        return $this->nicknameId;
    }

    public function getNicknameNickname(): ?string
    {
        return $this->nicknameNickname;
    }

    public function setNicknameNickname(string $nicknameNickname): static
    {
        $this->nicknameNickname = $nicknameNickname;

        return $this;
    }

    public function getCharacter(): ?ApiCharacters
    {
        return $this->character;
    }

    public function setCharacter(?ApiCharacters $character): static
    {
        $this->character = $character;

        return $this;
    }


}
