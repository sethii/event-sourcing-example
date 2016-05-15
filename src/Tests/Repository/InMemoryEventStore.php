<?php

namespace Tests\Repository;

use Domain\Event;
use Domain\EventStorePersister;
use Domain\Order;
use Domain\Order\OrderRepository;

class InMemoryEventStore implements OrderRepository, EventStorePersister
{
    /**
     * @var Event[]
     */
    private $events = [];

    /**
     * @param string $orderId
     * @return Order|null
     */
    public function findOrderById($orderId)
    {
        $orderEvents = [];

        foreach ($this->events as $event) {
            $eventParameters = $event->getParameters();

            if ($eventParameters['id'] == $orderId) {
                $orderEvents[] = $event;
            }
        }

        if (empty($orderEvents)) {
            return null;
        }

        return Order::reconstituteFrom($orderId, $orderEvents);
    }

    /**
     * @return int
     */
    public function getTotalOrders()
    {
        $uniqueOrders = [];

        foreach ($this->events as $event) {
            $parameter = $event->getParameters();

            if (!in_array($parameter['id'], $uniqueOrders)) {
                $uniqueOrders[] = $parameter['id'];
            }
        }

        return count($uniqueOrders);
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        if (empty($this->events)) {
            throw new \RuntimeException('There is no events to build order from');
        }

        $orderId = $this->events[0]->getParameters()['id'];

        return Order::reconstituteFrom($orderId, $this->events);
    }

    /**
     * @param Event[] $events
     */
    public function addEvents(array $events)
    {
        foreach ($events as $event) {
            $this->events[] = $event;
        }
    }

    public function clear()
    {
        $this->events = [];
    }
}
