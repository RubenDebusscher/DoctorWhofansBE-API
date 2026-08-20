<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsLogs
 */
#[ORM\Table(name: 'analytics__logs')]
#[ORM\Index(name: 'PL_Pagina', columns: ['PL_Pagina'])]
#[ORM\Entity]
class AnalyticsLogs
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'PL_id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $plId;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'PL_Pagina', type: 'integer', nullable: true)]
    private $plPagina = '0';

    /**
     * @var string
     */
    #[ORM\Column(name: 'PL_IP', type: 'text', length: 0, nullable: false)]
    private $plIp;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'PL_Timestamp', type: 'datetime', nullable: false, options: ['default' => null])]
    private $plTimestamp = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'PL_Session', type: 'string', length: 500, nullable: true)]
    private $plSession;

    /**
     * @var string
     */
    #[ORM\Column(name: 'PL_Pag', type: 'string', length: 200, nullable: false)]
    private $plPag;

    public function getPlId(): ?int
    {
        return $this->plId;
    }

    public function getPlPagina(): ?int
    {
        return $this->plPagina;
    }

    public function setPlPagina(?int $plPagina): static
    {
        $this->plPagina = $plPagina;

        return $this;
    }

    public function getPlIp(): ?string
    {
        return $this->plIp;
    }

    public function setPlIp(string $plIp): static
    {
        $this->plIp = $plIp;

        return $this;
    }

    public function getPlTimestamp(): ?\DateTime
    {
        return $this->plTimestamp;
    }

    public function setPlTimestamp(\DateTime $plTimestamp): static
    {
        $this->plTimestamp = $plTimestamp;

        return $this;
    }

    public function getPlSession(): ?string
    {
        return $this->plSession;
    }

    public function setPlSession(?string $plSession): static
    {
        $this->plSession = $plSession;

        return $this;
    }

    public function getPlPag(): ?string
    {
        return $this->plPag;
    }

    public function setPlPag(string $plPag): static
    {
        $this->plPag = $plPag;

        return $this;
    }


}
