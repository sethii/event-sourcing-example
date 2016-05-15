<?php

namespace Domain\Order\Event;

use Domain\Event;

class OrderClosedEvent implements Event
{
    private $orderId;

    public function __construct(
        $orderId
    ) {
        $this->orderId = $orderId;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return [
            'id' => $this->orderId,
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'OrderClosedEvent';
    }
}
