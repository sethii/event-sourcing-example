<?php

namespace Domain\Order\Event;

use Domain\Event;

class PositionAddedToOrderEvent implements Event
{
    private $orderId;
    private $positionId;
    private $positionName;
    private $positionPrice;
    private $positionOwner;

    public function __construct(
        $orderId,
        $positionOwner,
        $positionId,
        $positionName,
        $positionPrice
    ) {
        $this->orderId = $orderId;
        $this->positionId = $positionId;
        $this->positionName = $positionName;
        $this->positionPrice = $positionPrice;
        $this->positionOwner = $positionOwner;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return [
            'id' => $this->orderId,
            'positionId' => $this->positionId,
            'positionName' => $this->positionName,
            'positionPrice' => $this->positionPrice,
            'positionOwner' => $this->positionOwner
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'PositionAddedToOrderEvent';
    }
}
