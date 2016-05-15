<?php

namespace Infrastructure\Order\Handler;

use Domain\Order;
use Domain\EventStorePersister;
use Domain\Order\OrderRepository;
use Infrastructure\Order\Command\AddPositionToOrderCommand;

class AddPositionToOrderHandler
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
     * @param AddPositionToOrderCommand $addPositionToOrderCommand
     */
    public function handleAddPositionToOrderCommand(AddPositionToOrderCommand $addPositionToOrderCommand)
    {
        $order = $this->orderRepository->findOrderById($addPositionToOrderCommand->getOrderId());

        $order->addPosition(
            $addPositionToOrderCommand->getPositionOwner(),
            $addPositionToOrderCommand->getPositionName(),
            $addPositionToOrderCommand->getPositionPrice()
        );

        $this->eventStorePersister->addEvents($order->getRecentEvents());
    }
}
