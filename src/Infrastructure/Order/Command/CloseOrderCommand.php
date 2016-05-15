<?php

namespace Infrastructure\Order\Command;

class CloseOrderCommand
{
    private $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }
}
