<?php

namespace spec\Domain;

use Domain\Order\Event\OrderOpenedEvent;
use Domain\Order\Event\PositionAddedToOrderEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderSpec extends ObjectBehavior
{
    function it_opens_order()
    {
        $this->beConstructedWith('some_id');

        $this->open('some_supplier');

        $events = $this->getRecentEvents();

        $events->shouldHaveCount(1);
        $this->getAggregateId()->shouldBe('some_id');
        $this->getSupplierId()->shouldBe('some_supplier');
        $this->getStatus()->shouldBe('Opened');
    }

    function it_closes_order()
    {
        $this->beConstructedThrough('reconstituteFrom',[
            'some_id',
            [
                new OrderOpenedEvent('some_id', 'some_supplier_id')
            ]
        ]);

        $this->close();
        $this->getRecentEvents()->shouldHaveCount(1);

        $this->getStatus()->shouldBe('Closed');
    }

    function it_reconstitute_order_from_events()
    {
        $this->beConstructedThrough('reconstituteFrom',[
            'some_id',
            [
                new OrderOpenedEvent('some_id', 'some_supplier_id'),
                new PositionAddedToOrderEvent('some_id', 'some_owner', '1', 'some_name', '100'),
            ]
        ]);

        $this->getAggregateId()->shouldReturn('some_id');
        $this->getSupplierId()->shouldReturn('some_supplier_id');
        $this->getStatus()->shouldReturn('Opened');

        $this->getPositions()->shouldBeLike([
            [
                'id' => '1',
                'owner' => 'some_owner',
                'name' => 'some_name',
                'price' => '100'
            ]
        ]);
    }

    function it_adds_position_to_order()
    {
        $this->beConstructedThrough('reconstituteFrom',[
            'some_id',
            [
                new OrderOpenedEvent('some_id', 'some_supplier_id')
            ]
        ]);

        $this->addPosition('John Doe', 'Chicken', '13');

        $this->getRecentEvents()->shouldHaveCount(1);

        $this->getPositions()->shouldHaveCount(1);
        $position = $this->getPositions()[0];
        $position['id']->shouldBeString();
        $position['owner']->shouldBe('John Doe');
        $position['name']->shouldBe('Chicken');
        $position['price']->shouldBe('13');
    }
}
