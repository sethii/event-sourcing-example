Feature: Close order
  In order to stop other participants from adding new positions to order
  As a Owner
  I have to be able to close order

  Scenario: Order closing
    Given there is an order with id "1" in system
    When I close order with id "1"
    Then there should be "1" order in system with status "Closed"