<?php

namespace Domain;

interface EventStorePersister
{
    /**
     * @param Event[] $events
     */
    public function addEvents(array $events);
}
