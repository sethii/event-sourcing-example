<?php

class Order
{
    private $recentEvents;

    private $status;
    private $id;
    private $positions = [];
    private $supplierId;

    public function getId()
    {
        return $this->id;
    }

    public function getSupplierId()
    {
        return $this->supplierId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getPositions()
    {
        return $this->positions;
    }

    public function getRecentEvents()
    {
        $eventsCopy = $this->recentEvents;

        $this->recentEvents = [];

        return $eventsCopy;
    }

    public function open($supplierId)
    {
        $id = 'some_random_id_here';
        $openOrderEvent =  new OrderOpenedEvent($id, $supplierId);

        $this->recentEvents[] = $openOrderEvent;
        $this->apply($openOrderEvent);
    }

    public function close()
    {
        $closeOrderEvent = new OrderClosedEvent($this->id);

        $this->recentEvents[] = $closeOrderEvent;
        $this->apply($closeOrderEvent);
    }

    public function addPosition($owner, $id, $name, $price)
    {
        $positionAddedToOrderEvent = new PositionAddedToOrderEvent(
            $this->id,
            $owner,
            $id,
            $name,
            $price
        );

        $this->recentEvents[] = $positionAddedToOrderEvent;
        $this->apply($positionAddedToOrderEvent);
    }

    /**
     * @param Event[] $events
     * @return Order
     */
    public static function reconstituteFrom(array $events)
    {
        $order = new Order();

        foreach ($events as $event) {
            $order->apply($event);
        }

        return $order;
    }

    private function applyOrderClosedEvent(Event $orderClosedEvent)
    {
        $orderParameters = $orderClosedEvent->getParameters();

        $this->status = 'Closed';
    }

    private function applyOrderOpenedEvent(Event $orderOpenedEvent)
    {
        $orderParameters = $orderOpenedEvent->getParameters();

        $this->status = 'Opened';
        $this->id = $orderParameters['id'];
        $this->supplierId = $orderParameters['supplierId'];
    }

    private function applyPositionAddedToOrderEvent(Event $positionAddedToOrderEvent)
    {
        $orderParameters = $positionAddedToOrderEvent->getParameters();

        $this->positions[] = [
            'id' => $orderParameters['positionId'],
            'owner' => $orderParameters['positionOwner'],
            'name' => $orderParameters['positionName'],
            'price' => $orderParameters['positionPrice'],
        ];
    }

    /**
     * @param Event $event
     */
    private function apply(Event $event)
    {
        $method = 'apply' . $event->getType();

        $this->$method($event);
    }
}
