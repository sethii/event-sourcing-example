<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $order;

    /**
     * @Given I decide to open order for supplier with id :supplierId
     */
    public function iDecideToOpenOrderForSuuplierWithId($supplierId)
    {
        $this->order = new Order();
        $this->order->open($supplierId);
    }

    /**
     * @Then order should have :events recent events
     */
    public function orderShouldHaveRecentEvents($events)
    {
        $recentsEvents = $this->order->getRecentEvents();

        if (count($recentsEvents) != $events) {
            throw new RuntimeException("Invalid number of events");
        }
    }

    /**
     * @Then should have :status status
     */
    public function iShouldHaveOrderWithStatus($status)
    {
        if (!$this->order->getStatus() == $status) {
            throw new RuntimeException("Invalid status");
        }
    }
}
