Feature: Add new position to order
  In order to participate in order
  As a Participant
  I have to be able to add new position to order

  Scenario: Adding new position to order
    Given there is an order with id "1" in system
    When I decide to add position to order with id "1" with parameters:
      | positionOwner | positionName | positionPrice |
      | John Doe      | Chicken      | 100.00        |
    Then there should be "1" order in system with status "Opened"
    And it should have "1" position with parameters:
      | positionOwner | positionName | positionPrice |
      | John Doe      | Chicken      | 100.00        |