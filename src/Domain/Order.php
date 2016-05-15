<?php
namespace Domain;

use Domain\Order\Event\OrderOpenedEvent;
use Domain\Order\Event\OrderClosedEvent;
use Domain\Order\Event\PositionAddedToOrderEvent;

class Order extends AggregateRoot
{
    private $status;
    private $positions = [];
    private $supplierId;

    public function __construct($orderId)
    {
        $this->aggregateId = $orderId;
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

    public function open($supplierId)
    {
        $openOrderEvent =  new OrderOpenedEvent($this->aggregateId, $supplierId);

        $this->recentEvents[] = $openOrderEvent;
        $this->applyEvent($openOrderEvent);
    }

    public function close()
    {
        $closeOrderEvent = new OrderClosedEvent($this->aggregateId);

        $this->recentEvents[] = $closeOrderEvent;
        $this->applyEvent($closeOrderEvent);
    }

    public function addPosition($owner, $name, $price)
    {
        $positionAddedToOrderEvent = new PositionAddedToOrderEvent(
            $this->aggregateId,
            $owner,
            uniqid(),
            $name,
            $price
        );

        $this->recentEvents[] = $positionAddedToOrderEvent;
        $this->applyEvent($positionAddedToOrderEvent);
    }

    /**
     * @param string $orderId
     * @param Event[] $events
     * @return Order
     */
    public static function reconstituteFrom($orderId, array $events)
    {
        $order = new Order($orderId);

        foreach ($events as $event) {
            $order->applyEvent($event);
        }

        return $order;
    }

    protected function applyOrderClosedEvent(Event $orderClosedEvent)
    {
        $this->status = 'Closed';
    }

    protected function applyOrderOpenedEvent(Event $orderOpenedEvent)
    {
        $orderParameters = $orderOpenedEvent->getParameters();

        $this->status = 'Opened';
        $this->supplierId = $orderParameters['supplierId'];
    }

    protected function applyPositionAddedToOrderEvent(Event $positionAddedToOrderEvent)
    {
        $orderParameters = $positionAddedToOrderEvent->getParameters();

        $this->positions[] = [
            'id' => $orderParameters['positionId'],
            'owner' => $orderParameters['positionOwner'],
            'name' => $orderParameters['positionName'],
            'price' => $orderParameters['positionPrice'],
        ];
    }
}
