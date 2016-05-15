Feature: Open new order
  In order to collect orders from other participants
  As a User
  I have to be able to open order

  Scenario: Order opening
    Given there are no orders in system
    When I open order for supplier with id "supplier_id"
    Then there should be "1" order in system with status "Opened"
    And order should be placed for supplier with id "supplier_id"