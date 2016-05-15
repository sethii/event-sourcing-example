<?php

namespace Domain\Order\Event;

use Domain\Event;

class OrderOpenedEvent implements Event
{
    private $orderId;
    private $supplierId;

    public function __construct(
        $orderId,
        $supplierId
    ) {
        $this->orderId = $orderId;
        $this->supplierId = $supplierId;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return [
            'id' => $this->orderId,
            'supplierId' => $this->supplierId
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'OrderOpenedEvent';
    }
}
