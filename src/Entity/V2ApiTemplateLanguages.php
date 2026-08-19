<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use App\Entity\ManagementLanguages; // <-- Deze import is essentieel!

/**
 * V2ApiTemplateLanguages
 */
#[ORM\Table(name: 'V2__Api_Template_Languages')]
#[ORM\Index(name: 'Api_T_Id', columns: ['Api_T_Id'])]
#[ORM\Index(name: 'Api_L_Id', columns: ['Api_L_Id'])]
#[ORM\Entity]
class V2ApiTemplateLanguages
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'ApiTL_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $apitlId;

    /**
     * @var \ManagementLanguages
     */
    #[ORM\JoinColumn(name: 'Api_L_Id', referencedColumnName: 'language_Id')]
    #[ORM\ManyToOne(targetEntity: ManagementLanguages::class)]
    private ?ManagementLanguages $language;

    /**
     * @var \V2ApiTemplates
     */
    #[ORM\JoinColumn(name: 'Api_T_Id', referencedColumnName: 'Template_Id')]
    #[ORM\ManyToOne(targetEntity: \V2ApiTemplates::class)]
    private $apiT;

    public function getApitlId(): ?int
    {
        return $this->apitlId;
    }

    public function getLanguage(): ?ManagementLanguages
    {
        return $this->language;
    }

    public function setLanguage(?ManagementLanguages $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getApiT(): ?V2ApiTemplates
    {
        return $this->apiT;
    }

    public function setApiT(?V2ApiTemplates $apiT): static
    {
        $this->apiT = $apiT;

        return $this;
    }


}
