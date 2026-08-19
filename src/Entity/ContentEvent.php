<?php

namespace App\Entity;



use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContentEvent
 */
#[ORM\Table(name: 'content__event')]
#[ORM\Entity]
class ContentEvent
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'Event_Id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $eventId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Event_Name', type: 'string', length: 300, nullable: false)]
    private $eventName;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'Event_Start', type: 'datetime', nullable: false)]
    private $eventStart;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'Event_End', type: 'datetime', nullable: false)]
    private $eventEnd;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Event_Cal_Id', type: 'string', length: 800, nullable: false, options: ['default' => 'Event'])]
    private $eventCalId = 'Event';

    /**
     * @var string
     */
    #[ORM\Column(name: 'Event_Body', type: 'text', length: 0, nullable: false)]
    private $eventBody;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Event_Category', type: 'text', length: 65535, nullable: false)]
    private $eventCategory;

    /**
     * @var int
     */
    #[ORM\Column(name: 'Event_State', type: 'integer', nullable: false)]
    private $eventState;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'Event_isReadOnly', type: 'boolean', nullable: false, options: ['default' => '1'])]
    private $eventIsreadonly = true;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Event_Color', type: 'text', length: 16777215, nullable: false)]
    private $eventColor;

    /**
     * @var string
     */
    #[ORM\Column(name: 'Event_BgColor', type: 'text', length: 16777215, nullable: false)]
    private $eventBgcolor;

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getEventStart(): ?\DateTime
    {
        return $this->eventStart;
    }

    public function setEventStart(\DateTime $eventStart): static
    {
        $this->eventStart = $eventStart;

        return $this;
    }

    public function getEventEnd(): ?\DateTime
    {
        return $this->eventEnd;
    }

    public function setEventEnd(\DateTime $eventEnd): static
    {
        $this->eventEnd = $eventEnd;

        return $this;
    }

    public function getEventCalId(): ?string
    {
        return $this->eventCalId;
    }

    public function setEventCalId(string $eventCalId): static
    {
        $this->eventCalId = $eventCalId;

        return $this;
    }

    public function getEventBody(): ?string
    {
        return $this->eventBody;
    }

    public function setEventBody(string $eventBody): static
    {
        $this->eventBody = $eventBody;

        return $this;
    }

    public function getEventCategory(): ?string
    {
        return $this->eventCategory;
    }

    public function setEventCategory(string $eventCategory): static
    {
        $this->eventCategory = $eventCategory;

        return $this;
    }

    public function getEventState(): ?int
    {
        return $this->eventState;
    }

    public function setEventState(int $eventState): static
    {
        $this->eventState = $eventState;

        return $this;
    }

    public function isEventIsreadonly(): ?bool
    {
        return $this->eventIsreadonly;
    }

    public function setEventIsreadonly(bool $eventIsreadonly): static
    {
        $this->eventIsreadonly = $eventIsreadonly;

        return $this;
    }

    public function getEventColor(): ?string
    {
        return $this->eventColor;
    }

    public function setEventColor(string $eventColor): static
    {
        $this->eventColor = $eventColor;

        return $this;
    }

    public function getEventBgcolor(): ?string
    {
        return $this->eventBgcolor;
    }

    public function setEventBgcolor(string $eventBgcolor): static
    {
        $this->eventBgcolor = $eventBgcolor;

        return $this;
    }


}
