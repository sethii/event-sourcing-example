<?php

namespace Infrastructure\Order\Handler;

use Domain\Order;
use Domain\EventStorePersister;
use Infrastructure\Order\Command\OpenOrderCommand;

class OpenOrderHandler
{
    /**
     * @var EventStorePersister
     */
    private $eventStorePersister;

    /**
     * @param EventStorePersister $eventStorePersister
     */
    public function __construct(EventStorePersister $eventStorePersister)
    {
        $this->eventStorePersister = $eventStorePersister;
    }

    /**
     * @param OpenOrderCommand $openOrderCommand
     */
    public function handleOpenOrderCommand(OpenOrderCommand $openOrderCommand)
    {
        $order = new Order(uniqid());

        $order->open($openOrderCommand->getSupplierId());

        $this->eventStorePersister->addEvents($order->getRecentEvents());
    }
}
