Feature: Open order
  In order to place an order
  As a user
  I need to be able to open user

  Scenario: Opening order
    When I decide to open order for supplier with id "1"
    Then order should have "1" recent events
    And should have "Opened" status