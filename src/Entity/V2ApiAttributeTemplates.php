<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * V2ApiAttributeTemplates
 */
#[ORM\Table(name: 'V2__Api_Attribute_Templates')]
#[ORM\Index(name: 'Api_L_Id', columns: ['Api_A_Id'])]
#[ORM\Index(name: 'Api_T_Id', columns: ['Api_T_Id'])]
#[ORM\Entity]
class V2ApiAttributeTemplates
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'ApiAT_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $apiatId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'Api_T_Id', type: 'integer', nullable: false)]
    private $apiTId;

    /**
     * @var int
     */
    #[ORM\Column(name: 'Api_A_Id', type: 'integer', nullable: false)]
    private $apiAId;

    public function getApiatId(): ?int
    {
        return $this->apiatId;
    }

    public function getApiTId(): ?int
    {
        return $this->apiTId;
    }

    public function setApiTId(int $apiTId): static
    {
        $this->apiTId = $apiTId;

        return $this;
    }

    public function getApiAId(): ?int
    {
        return $this->apiAId;
    }

    public function setApiAId(int $apiAId): static
    {
        $this->apiAId = $apiAId;

        return $this;
    }


}
