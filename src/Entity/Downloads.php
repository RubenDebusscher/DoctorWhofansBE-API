<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * Downloads
 */
#[ORM\Table(name: 'downloads')]
#[ORM\Entity]
class Downloads
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'download_ID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $downloadId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'download_Type', type: 'string', length: 50, nullable: false)]
    private $downloadType;

    /**
     * @var string
     */
    #[ORM\Column(name: 'download_Naam', type: 'string', length: 255, nullable: false)]
    private $downloadNaam;

    /**
     * @var string
     */
    #[ORM\Column(name: 'download_link', type: 'string', length: 255, nullable: false)]
    private $downloadLink;

    /**
     * @var string
     */
    #[ORM\Column(name: 'download_Taal', type: 'string', length: 50, nullable: false)]
    private $downloadTaal;

    /**
     * @var int
     */
    #[ORM\Column(name: 'download_pagina', type: 'integer', nullable: false)]
    private $downloadPagina;

    /**
     * @var int
     */
    #[ORM\Column(name: 'D_Owner', type: 'integer', nullable: false)]
    private $dOwner;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'D_File', type: 'string', length: 900, nullable: true)]
    private $dFile;

    public function getDownloadId(): ?int
    {
        return $this->downloadId;
    }

    public function getDownloadType(): ?string
    {
        return $this->downloadType;
    }

    public function setDownloadType(string $downloadType): static
    {
        $this->downloadType = $downloadType;

        return $this;
    }

    public function getDownloadNaam(): ?string
    {
        return $this->downloadNaam;
    }

    public function setDownloadNaam(string $downloadNaam): static
    {
        $this->downloadNaam = $downloadNaam;

        return $this;
    }

    public function getDownloadLink(): ?string
    {
        return $this->downloadLink;
    }

    public function setDownloadLink(string $downloadLink): static
    {
        $this->downloadLink = $downloadLink;

        return $this;
    }

    public function getDownloadTaal(): ?string
    {
        return $this->downloadTaal;
    }

    public function setDownloadTaal(string $downloadTaal): static
    {
        $this->downloadTaal = $downloadTaal;

        return $this;
    }

    public function getDownloadPagina(): ?int
    {
        return $this->downloadPagina;
    }

    public function setDownloadPagina(int $downloadPagina): static
    {
        $this->downloadPagina = $downloadPagina;

        return $this;
    }

    public function getDOwner(): ?int
    {
        return $this->dOwner;
    }

    public function setDOwner(int $dOwner): static
    {
        $this->dOwner = $dOwner;

        return $this;
    }

    public function getDFile(): ?string
    {
        return $this->dFile;
    }

    public function setDFile(?string $dFile): static
    {
        $this->dFile = $dFile;

        return $this;
    }


}
