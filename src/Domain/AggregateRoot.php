<?php
namespace Domain;

class AggregateRoot
{
    /**
     * @var Event[]
     */
    protected $recentEvents;

    /**
     * @var string
     */
    protected $aggregateId;

    public function getRecentEvents()
    {
        $eventsCopy = $this->recentEvents;
        $this->recentEvents = [];

        return $eventsCopy;
    }

    /**
     * @param Event $event
     */
    protected function applyEvent(Event $event)
    {
        $method = 'apply' . $event->getType();

        $this->$method($event);
    }

    /**
     * @return string
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }
}
