<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Infrastructure\Order\Command\OpenOrderCommand;
use Infrastructure\Order\Command\CloseOrderCommand;
use Infrastructure\Order\Command\AddPositionToOrderCommand;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $commandBus;
    private $inMemoryEventStore;

    public function __construct()
    {
        $this->inMemoryEventStore = new \Tests\Repository\InMemoryEventStore();
        $locator = new \League\Tactician\Handler\Locator\InMemoryLocator();

        $locator->addHandler(
            new \Infrastructure\Order\Handler\OpenOrderHandler($this->inMemoryEventStore),
            OpenOrderCommand::class
        );

        $locator->addHandler(
            new \Infrastructure\Order\Handler\CloseOrderHandler(
                $this->inMemoryEventStore,
                $this->inMemoryEventStore
            ),
            CloseOrderCommand::class
        );

        $locator->addHandler(
            new \Infrastructure\Order\Handler\AddPositionToOrderHandler(
                $this->inMemoryEventStore,
                $this->inMemoryEventStore
            ),
            AddPositionToOrderCommand::class
        );

        $handlerMiddleware = new \League\Tactician\Handler\CommandHandlerMiddleware(
            new \League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
            $locator,
            new \League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector()
        );

        $this->commandBus = new \League\Tactician\CommandBus([$handlerMiddleware]);
    }

    /**
     * @Given there is an order with id :orderId in system
     */
    public function thereIsAnOrderWithIdInSystem($orderId)
    {
        $order = new \Domain\Order($orderId);
        $order->open('supplierId');

        $this->inMemoryEventStore->addEvents($order->getRecentEvents());
    }

    /**
     * @When I close order with id :id
     */
    public function whenICloseOrderWithId($orderId)
    {
        $this->commandBus->handle(new CloseOrderCommand($orderId));
    }

    /**
     * @When I decide to add position to order with id :orderId with parameters:
     */
    public function iDecideToAddPositionToORderWithIdWithParameters($orderId, TableNode $parameters)
    {
        $positionParameters = $parameters->getHash()[0];

        $this->commandBus->handle(new AddPositionToOrderCommand(
            $orderId,
            $positionParameters['positionOwner'],
            $positionParameters['positionName'],
            $positionParameters['positionPrice']
        ));
    }


    /**
     * @Given there are no orders in system
     */
    public function thereAreNoOrdersInSystem()
    {
        $this->inMemoryEventStore->clear();
    }

    /**
     * @When I open order for supplier with id :supplierId
     */
    public function iOpenOrderForSupplierWithId($supplierId)
    {
        $this->commandBus->handle(new OpenOrderCommand($supplierId));
    }

    /**
     * @Then there should be :orderCount order in system with status :status
     */
    public function thereShouldBeOrderInSystemWithStatus($orderCount, $status)
    {
        if ($this->inMemoryEventStore->getTotalOrders() != $orderCount) {
            throw new \RuntimeException('Invalid order numbers');
        }

        if ($this->inMemoryEventStore->getOrder()->getStatus() != $status) {
            throw new \RuntimeException('Invalid order status');
        }
    }

    /**
     * @Then order should be placed for supplier with id :supplierId
     */
    public function orderShouldBePlacedForSupplierWithId($supplierId)
    {
        if ($this->inMemoryEventStore->getOrder()->getSupplierId() != $supplierId) {
            throw new \RuntimeException('Invalid order supplier id');
        }
    }

    /**
     * @Then it should have :positionCount position with parameters:
     */
    public function itShouldHavePositionWithParameters($positionCount, TableNode $parameters)
    {
        $order = $this->inMemoryEventStore->getOrder();
        $positionParameters = $parameters->getHash()[0];

        if (count($order->getPositions()) != $positionCount) {
            throw new \RuntimeException("Invalid position count");
        }

        $position = $order->getPositions()[0];

        if ($position['owner'] != $positionParameters['positionOwner']) {
            throw new \RuntimeException("Invalid position owner");
        }

        if ($position['name'] != $positionParameters['positionName']) {
            throw new \RuntimeException("Invalid position name");
        }

        if ($position['price'] != $positionParameters['positionPrice']) {
            throw new \RuntimeException("Invalid position price");
        }
    }
}
