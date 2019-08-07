<?php

namespace AlecRabbit\TWRBCore\Counters;

/**
 * Class EventsCounter
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class EventsCounter extends TimedCounter
{
    /**
     * Alias to add() method
     * @param int|null $time
     */
    public function addEvent(?int $time = null): void
    {
        $this->add($time);
    }

    /**
     * @return array
     */
    public function getRawEventsData(): array
    {
        return $this->getRawData();
    }

    /**
     * @param bool|null $reset
     * @return array
     */
    public function getEventsArray(?bool $reset = null): array
    {
        return $this->getDataArray($reset);
    }

    /**
     * @param bool|null $reset
     * @return object
     */
    public function getEvents(?bool $reset = null): object
    {
        return $this->getDataObject($reset);
    }
}
