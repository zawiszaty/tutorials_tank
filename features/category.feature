Feature: Category feature
  Scenario: Adding a new category
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I add "Authorization" header equal to "Bearer AdminTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg"
    And I send a "POST" request to "/api/v1/category" with body:
    """
    {
      "name": "King"
    }
    """
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    Then the response status code should be 200

  Scenario: Edit a new category
    When I add Category to databse
    And I send edit request
    Then The Category was be updated


  Scenario: Delete a category
    When I add Category to databse
    And I send delete request
    Then The Category was be deleted


