<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * News
 */
#[ORM\Table(name: 'News')]
#[ORM\Entity]
class News
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Titel', type: 'text', length: 65535, nullable: false)]
    private $titel;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Foto', type: 'text', length: 65535, nullable: false)]
    private $foto;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Bericht', type: 'text', length: 65535, nullable: false)]
    private $bericht;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'Datum', type: 'datetime', nullable: false)]
    private $datum;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Class', type: 'text', length: 65535, nullable: false)]
    private $class;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Width', type: 'string', length: 11, nullable: false)]
    private $width;

    /**
     * @var string
     */
    #[ORM\Column(name: 'IMG_Width', type: 'text', length: 65535, nullable: false)]
    private $imgWidth;

    /**
     * @var string
     */
    #[ORM\Column(name: 'alt', type: 'text', length: 65535, nullable: false)]
    private $alt;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Class_Text', type: 'text', length: 65535, nullable: false)]
    private $classText;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'actief', type: 'boolean', nullable: false, options: ['comment' => '0=onzichtbaar'])]
    private $actief;

    /**
     * @var int
     */
    #[ORM\Column(name: 'N_Owner', type: 'integer', nullable: false)]
    private $nOwner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitel(): ?string
    {
        return $this->titel;
    }

    public function setTitel(string $titel): static
    {
        $this->titel = $titel;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): static
    {
        $this->foto = $foto;

        return $this;
    }

    public function getBericht(): ?string
    {
        return $this->bericht;
    }

    public function setBericht(string $bericht): static
    {
        $this->bericht = $bericht;

        return $this;
    }

    public function getDatum(): ?\DateTime
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum): static
    {
        $this->datum = $datum;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(string $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getImgWidth(): ?string
    {
        return $this->imgWidth;
    }

    public function setImgWidth(string $imgWidth): static
    {
        $this->imgWidth = $imgWidth;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): static
    {
        $this->alt = $alt;

        return $this;
    }

    public function getClassText(): ?string
    {
        return $this->classText;
    }

    public function setClassText(string $classText): static
    {
        $this->classText = $classText;

        return $this;
    }

    public function isActief(): ?bool
    {
        return $this->actief;
    }

    public function setActief(bool $actief): static
    {
        $this->actief = $actief;

        return $this;
    }

    public function getNOwner(): ?int
    {
        return $this->nOwner;
    }

    public function setNOwner(int $nOwner): static
    {
        $this->nOwner = $nOwner;

        return $this;
    }


}
