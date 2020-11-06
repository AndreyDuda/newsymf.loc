<?php

declare(strict_types=1);

namespace App\Model;

class EventsTrait
{
    private $recordedEvents = [];

    protected function recordEvent(object $event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function releaseEvent(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }
}