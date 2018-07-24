Feature: Category feature
  Scenario: Adding a new category
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/category" with body:
    """
    {
      "name": "King"
    }
    """
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"

  Scenario: Edit a new category
    When I add Category to databse
    And I send edit request
    Then The Category was be updated


  Scenario: Delete a category
    When I add Category to databse
    And I send delete request
    Then The Category was be deleted
#    When I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    And I send a "Delete" request to "/category/f24c3526-8d75-11e8-9eb6-529269fb1459"
#    And the response should be in JSON
#    And the response should be equal to
#    """
#    "success"
#    """
#    And the header "Content-Type" should be equal to "application/json"
#    And The Category was be deleted


