<?php

namespace Infrastructure\Order\Handler;

use Domain\Order;
use Domain\EventStorePersister;
use Domain\Order\OrderRepository;
use Infrastructure\Order\Command\CloseOrderCommand;

class CloseOrderHandler
{
    /**
     * @var EventStorePersister
     */
    private $eventStorePersister;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @param OrderRepository $orderRepository
     * @param EventStorePersister $eventStorePersister
     */
    public function __construct(OrderRepository $orderRepository, EventStorePersister $eventStorePersister)
    {
        $this->eventStorePersister = $eventStorePersister;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param CloseOrderCommand $closeOrderCommand
     */
    public function handleCloseOrderCommand(CloseOrderCommand $closeOrderCommand)
    {
        $order = $this->orderRepository->findOrderById($closeOrderCommand->getOrderId());

        $order->close();

        $this->eventStorePersister->addEvents($order->getRecentEvents());
    }
}
