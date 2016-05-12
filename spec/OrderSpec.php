<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderSpec extends ObjectBehavior
{
    function it_opens_order()
    {
        $this->beConstructedWith();

        $this->open('some_supplier');

        $events = $this->getRecentEvents();

        $events->shouldHaveCount(1);
        $this->getId()->shouldBeString();
        $this->getSupplierId()->shouldBe('some_supplier');
        $this->getStatus()->shouldBe('Opened');
    }

    function it_closes_order()
    {
        $this->beConstructedThrough('reconstituteFrom',[
            [
                new \OrderOpenedEvent('some_id', 'some_supplier_id')
            ]
        ]);

        $this->close();
        $this->getRecentEvents()->shouldHaveCount(1);

        $this->getStatus()->shouldBe('Closed');
    }

    function it_reconstitute_order_from_events()
    {
        $this->beConstructedThrough('reconstituteFrom',[
            [
                new \OrderOpenedEvent('some_id', 'some_supplier_id')
            ]
        ]);

        $this->getId()->shouldReturn('some_id');
        $this->getSupplierId()->shouldReturn('some_supplier_id');
        $this->getStatus()->shouldReturn('Opened');
    }

    function it_adds_position_to_order()
    {
        $this->beConstructedThrough('reconstituteFrom',[
            [
                new \OrderOpenedEvent('some_id', 'some_supplier_id')
            ]
        ]);

        $this->addPosition('John Doe', '1', 'Chicken', '13');

        $this->getRecentEvents()->shouldHaveCount(1);

        $this->getPositions()->shouldBeLike([
            [
                'id' => '1',
                'owner' => 'John Doe',
                'name' => 'Chicken',
                'price' => '13'
            ]
        ]);
    }

    function it_should_fail()
    {
        $this->beConstructedThrough('reconstituteFrom',[
            [
                new \OrderOpenedEvent('some_id', 'some_supplier_id')
            ]
        ]);

        $this->getStatus()->shouldReturn("FAIL");
    }
}
