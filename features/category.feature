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
    And the response should be equal to
    """
    "success"
    """
    And the header "Content-Type" should be equal to "application/json"

