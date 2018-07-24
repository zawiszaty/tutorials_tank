Feature: Category feature
  Scenario: Adding a new category
    When I have Category in database
    And I add "Content-Type" header equal to "application/json"
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

#  Scenario: Edit a new category
#    When I have Category in database
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    And I send a "PUT" request to "/category" with body:
#    """
#    {
#      "id": "d55874ef-e2b2-4d03-a164-54bbfbdd9599",
#      "name": "King2"
#    }
#    """
#    And the response should be in JSON
#    And the response should be equal to
#    """
#    "success"
#    """
#    And the header "Content-Type" should be equal to "application/json"
#    And The Category was be changed

#  Scenario: Delete a category
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


