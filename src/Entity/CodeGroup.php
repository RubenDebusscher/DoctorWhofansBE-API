<?php
namespace App\Entity;

use App\Repository\CodeGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CodeGroupRepository::class)]
#[ORM\Table(name: 'code_groups')]
class CodeGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 100, unique: true)]
    private string $codeKey;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Uitgebreide documentatie / handleiding voor intern gebruik (bijv. Markdown/HTML)
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $documentation = null;

    /**
     * @var Collection<int, Code>
     */
    #[ORM\OneToMany(targetEntity: Code::class, mappedBy: 'codeGroup', cascade: ['persist', 'remove'])]
    private Collection $codes;

    public function __construct()
    {
        $this->codes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeKey(): string
    {
        return $this->codeKey;
    }

    public function setCodeKey(string $codeKey): static
    {
        $this->codeKey = $codeKey;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDocumentation(): ?string
    {
        return $this->documentation;
    }

    public function setDocumentation(?string $documentation): static
    {
        $this->documentation = $documentation;
        return $this;
    }

    /**
     * @return Collection<int, Code>
     */
    public function getCodes(): Collection
    {
        return $this->codes;
    }

    public function addCode(Code $code): static
    {
        if (!$this->codes->contains($code)) {
            $this->codes->add($code);
            $code->setCodeGroup($this);
        }
        return $this;
    }

    public function removeCode(Code $code): static
    {
        if ($this->codes->removeElement($code)) {
            if ($code->getCodeGroup() === $this) {
                $code->setCodeGroup(null);
            }
        }
        return $this;
    }
}
