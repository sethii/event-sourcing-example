<?php

namespace Domain\Order;

use Domain\Order;

interface OrderRepository
{
    /**
     * @param $orderId
     * @return Order
     */
    public function findOrderById($orderId);

    /**
     * @return int
     */
    public function getTotalOrders();
}
