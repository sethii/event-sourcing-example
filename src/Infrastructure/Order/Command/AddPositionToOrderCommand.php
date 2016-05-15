<?php

namespace Infrastructure\Order\Command;

class AddPositionToOrderCommand
{
    private $orderId;
    private $positionOwner;
    private $positionName;
    private $positionPrice;

    public function __construct($orderId, $positionOwner, $positionName, $positionPrice)
    {
        $this->orderId = $orderId;
        $this->positionOwner = $positionOwner;
        $this->positionName = $positionName;
        $this->positionPrice = $positionPrice;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return mixed
     */
    public function getPositionOwner()
    {
        return $this->positionOwner;
    }

    /**
     * @return mixed
     */
    public function getPositionName()
    {
        return $this->positionName;
    }

    /**
     * @return mixed
     */
    public function getPositionPrice()
    {
        return $this->positionPrice;
    }
}
